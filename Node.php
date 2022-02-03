<?php

class Node implements NodeInterface
{
    private $name;
    private $index = 1;
    private $childs = [];

    public function __construct(string $name)
    {
        return $this->name = $name;
    }

    public function __toString(): string
    {
        $result = str_repeat("+ ", $this->index) . $this->name . "\n";
        foreach ($this->childs as $child) {
            $child->index = $this->index + 1;
            $result .= $child;
        };
        return $result;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array
    {
        return $this->childs;
    }

    public function addChild(Node $node): self
    {
        $this->childs[] = $node;
        return $this;
    }
}
