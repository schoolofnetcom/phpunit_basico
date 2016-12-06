<?php

class ProductTest extends PHPUnit_Framework_TestCase
{

    private $product;

    protected function setUp()
    {
        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()->getMock();
        $this->product = new \SON\Model\Product($pdo);
    }

    public function testIfIdIsZero()
    {
        $this->assertEquals(0,$this->product->getId());
    }

    public function testIfTotalIsZero()
    {
        $this->assertEquals(0.0,$this->product->getTotal());
    }

    /**
     * @dataProvider collectionData
     */
    public function testEncapsulate($property, $expected)
    {

        $null = $this->product->{'get' . ucfirst($property)}();
        if(!is_float($expected) && !is_int($expected)) {
            $this->assertNull($null);
        }else{
            if(is_float($expected)){
                $this->assertEquals(0.0, $null);
            }else{
                $this->assertEquals(0, $null);
            }
        }

        $result = $this->product->{'set' . ucfirst($property)}($expected);
        $this->assertInstanceOf(\SON\Model\Product::class, $result);

        $actual = $this->product->{'get' . ucfirst($property)}();
        $this->assertEquals($expected, $actual);
    }

    public function collectionData()
    {
        return [
            ['name', 'Produto 1'],
            ['price', 10.11],
            ['quantity', 5],
        ];
    }
}