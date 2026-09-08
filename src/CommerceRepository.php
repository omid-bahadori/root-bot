<?php

final class CommerceRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function balance(string $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT user_id, balance, currency FROM rootbot_wallets WHERE user_id = ? LIMIT 1'
        );
        $statement->execute([$userId]);
        $wallet = $statement->fetch();

        return $wallet ?: ['user_id' => $userId, 'balance' => '0.00', 'currency' => 'IRR'];
    }

    public function createTopup(string $userId, string $amount, string $currency, string $gateway): array
    {
        $paymentRef = 'pay_' . bin2hex(random_bytes(12));
        $statement = $this->pdo->prepare(
            'INSERT INTO rootbot_payments
                (payment_ref, user_id, amount, currency, gateway, status)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([$paymentRef, $userId, $amount, $currency, $gateway, 'pending']);

        return [
            'payment_ref' => $paymentRef,
            'user_id' => $userId,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
        ];
    }

    public function createOrder(
        string $userId,
        string $serviceId,
        string $amount,
        string $currency,
        array $payload = []
    ): array {
        $orderRef = 'ord_' . bin2hex(random_bytes(12));
        $statement = $this->pdo->prepare(
            'INSERT INTO rootbot_orders
                (order_ref, user_id, service_id, amount, currency, status, payload)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->execute([
            $orderRef,
            $userId,
            $serviceId,
            $amount,
            $currency,
            'pending',
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);

        return $this->order($orderRef, $userId);
    }

    public function order(string $orderRef, ?string $userId = null): ?array
    {
        $sql = 'SELECT order_ref, user_id, service_id, amount, currency, status, payload, created_at, updated_at
                FROM rootbot_orders WHERE order_ref = ?';
        $parameters = [$orderRef];
        if ($userId !== null) {
            $sql .= ' AND user_id = ?';
            $parameters[] = $userId;
        }
        $sql .= ' LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
        $order = $statement->fetch();
        if (!$order) {
            return null;
        }

        $order['payload'] = $order['payload'] === null ? null : json_decode($order['payload'], true);
        return $order;
    }
}
