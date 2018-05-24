<?php

namespace App\Entity;

class BlockChain
{
    /**
     * @var array
     */
    private $blockTree = [];

    /**
     * @return Block[]
     */
    public function getBlockChain(): array
    {
        $blocks = [];
        $this->longestChain($this->blockTree, $blocks);

        reset($this->blockTree);
        $root = current($this->blockTree);
        array_unshift($blocks, $root);

        return $blocks;
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function validateBlock(Block $block): bool
    {
        return count($block->getTransactions()) >= 1 && !$this->hasBlock($this->blockTree, $block);
    }

    /**
     * @param int $parentBlockId
     * @param Block $block
     */
    public function addBlock(?int $parentBlockId, Block $block): void
    {
        if (!$this->validateBlock($block)) {
            return;
        }

        if (null === $parentBlockId) {
            if (!empty($this->blockTree)) {
                return;
            }
            $this->createRoot($block);

            return;
        }

        $parentNode = &$this->findNode($this->blockTree, $parentBlockId);
        if (null === $parentNode) {
            return;
        }
        if ($this->allTransactionsHasEnoughCoins($block, $parentNode)) {
            $parentNode['children'][] = [
                'block' => $block,
                'children' => [],
                'parent' => $parentNode,
            ];
        }
    }

    /**
     * @param Block $block
     * @param array $parentNode
     * @return bool
     */
    private function allTransactionsHasEnoughCoins(Block $block, array $parentNode): bool
    {
        $chain = [];
        while (isset($parentNode['parent'])) {
            $chain[] = $parentNode['block'];
            $parentNode = $parentNode['parent'];
        }
        array_unshift($chain, $block);

        $balances = [];
        /** @var Block $block */
        foreach (array_reverse($chain) as $block) {
            foreach ($block->getTransactions() as $transaction) {
                foreach ($transaction->getAccounts() as $account) {
                    if (!isset($balances[$account->getName()])) {
                        $balance = 0;
                        $balances[$account->getName()] = $balance;
                    } else {
                        $balance = $balances[$account->getName()];
                    }
                    $balance = $transaction->countBalance($account, $balance);
                    if ($balance < 0) {
                        return false;
                    } else  {
                        $balances[$account->getName()] = $balance;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param Account $account
     * @return int
     */
    public function getBalance(Account $account): int
    {
        $balance = 0;
        $blockChain = $this->getBlockChain();
        foreach ($blockChain as $block) {
            foreach ($block->getTransactions() as $transaction) {
                $balance = $transaction->countBalance($account, $balance);
            }
        }

        return $balance;
    }

    /**
     * @param array $node
     * @param Block $block
     * @return bool
     */
    private function hasBlock(array $node, Block $block): bool
    {
        $result = $this->findNode($node, $block->getId());

        return !empty($result);
    }

    /**
     * @param array $node
     * @param int $blockId
     * @return array|null
     */
    private function &findNode(array &$node, int $blockId): ?array
    {
        $notFound = null;
        if (empty($node)) {
            return $notFound;
        }

        if ($node['block']->getId() == $blockId) {
            return $node;
        }

        foreach ($node['children'] as &$child) {
            return $this->findNode($child, $blockId);
        }

        return $notFound;
    }

    /**
     * @param Block $block
     */
    private function createRoot(Block $block): void
    {
        $this->blockTree['block'] = $block;
        $this->blockTree['children'] = [];
        $this->blockTree['parent'] = [];
    }

    /**
     * @param array $node
     * @return int
     */
    private function getTreeMaxDepth(array $node): int
    {
        $maxDepth = 0;
        foreach ($node['children'] as $child) {
            $maxDepth = max($maxDepth, $this->getTreeMaxDepth($child));
        }

        return $maxDepth + 1;
    }

    /**
     * @param array $node
     * @param array $blocks
     */
    private function longestChain($node, &$blocks): void
    {
        $maxDepth = 0;
        $deepestPath = null;
        foreach ($node['children'] as $id => $child) {
            $depth = $this->getTreeMaxDepth($child);
            if ($maxDepth < $depth) {
                $deepestPath = $child;
                $maxDepth = $depth;
            }
        }

        if ($deepestPath) {
            $blocks[] = $deepestPath['block'];
            $this->longestChain($deepestPath, $blocks);
        }
    }
}