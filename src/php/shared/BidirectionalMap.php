<?php
class BidirectionalMap {
    //f - forward
    //b - backward
    private array $f, $b;

    public function __constructor(){
        $f = [];
        $b = [];
    }

    public function set(string $forwardKey, string $backwardKey):BidirectionalMap {
        $this->f[$forwardKey] = $backwardKey;
        $this->b[$backwardKey] = $forwardKey;
        return $this;
    }

    public function setAll(array $to_add):BidirectionalMap {
        foreach ($to_add as $column) {
            if(count($column) === 1)
                $column[1] = $column[0];
            $this->set($column[0], $column[1]);
        }

        return $this;
    }

    public function forward(string $forwardKey): string{
        return $this->f[$forwardKey];
    }

    public function backward(string $backwardKey): string{
        return $this->b[$backwardKey];
    }

    public function forwardExists(string $forwardKey): bool{
        return array_key_exists($forwardKey, $this->f);
    }

    public function backwardExists(string $backwardKey): string{
        return array_key_exists($backwardKey, $this->b);
    }

}