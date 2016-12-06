<?php
declare(strict_types = 1);

namespace SON\Model;


class Product
{
    private $id;
    private $name;
    private $price;
    private $quantity;
    private $total;

    private $pdo;

    /**
     * Product constructor.
     * @param $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return (int) $this->id;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice(): ?float
    {
        return (float) $this->price;
    }

    /**
     * @param mixed $price
     * @return Product
     */
    public function setPrice($price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity(): ?int
    {
        return (int) $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return Product
     */
    public function setQuantity($quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal(): ?float
    {
        return (float) $this->total;
    }

    private function hydrate(array $data)
    {
        $this->id = $data['id'];
        $this->setName($data['name'])
            ->setPrice($data['price'])
            ->setQuantity($data['quantity']);
        $this->total = $data['total'];
    }


    public function save(array $data): Product
    {
        if(!isset($data['id'])) {
            $query = "INSERT INTO products (`name`,`price`,`quantity`,`total`) VALUES (:name,:price,:quantity,:total)";
            $stmt = $this->pdo->prepare($query);
        }else{
            $query = "UPDATE products set `name`=:name,`price`=:price,`quantity`=:quantity,`total`=:total WHERE `id`=:id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(":id", $data['id']);
        }

        $stmt->bindValue(":name", $data['name']);
        $stmt->bindValue(":price", $data['price']);
        $stmt->bindValue(":quantity", $data['quantity']);
        $data['total'] = $data['price'] * $data['quantity'];
        $stmt->bindValue(":total", $data['total']);

        $stmt->execute();
        $data['id'] = $data['id'] ?? $this->pdo->lastInsertId();
        $this->hydrate($data);
        return $this;
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function all()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): Product
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(!$data){
            throw new \Exception('Produto não existente.');
        }
        $this->hydrate($data);
        return $this;
    }
}