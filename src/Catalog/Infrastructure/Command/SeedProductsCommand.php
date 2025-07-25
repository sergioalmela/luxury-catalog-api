<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Command;

use App\Catalog\Domain\Entity\Product;
use App\Catalog\Domain\ValueObject\Category;
use App\Catalog\Domain\ValueObject\Name;
use App\Catalog\Domain\ValueObject\Price;
use App\Catalog\Domain\ValueObject\SKU;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'product:seed',
    description: 'Clear database and seed with the 5 sample products'
)]
final class SeedProductsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Clearing database and seeding sample products...');

        $this->entityManager->createQuery('DELETE FROM App\Catalog\Domain\Entity\Product')->execute();

        $sampleProducts = [
            ['sku' => '000001', 'name' => 'BV Lean leather ankle boots', 'category' => 'boots', 'price' => 89000],
            ['sku' => '000002', 'name' => 'BV Lean leather ankle boots', 'category' => 'boots', 'price' => 99000],
            ['sku' => '000003', 'name' => 'Ashlington leather ankle boots', 'category' => 'boots', 'price' => 71000],
            ['sku' => '000004', 'name' => 'Naima embellished suede sandals', 'category' => 'sandals', 'price' => 79500],
            ['sku' => '000005', 'name' => 'Nathane leather sneakers', 'category' => 'sneakers', 'price' => 59000],
        ];

        foreach ($sampleProducts as $sampleProduct) {
            $product = Product::create(
                SKU::of($sampleProduct['sku']),
                Name::of($sampleProduct['name']),
                Category::of($sampleProduct['category']),
                Price::of($sampleProduct['price'])
            );

            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();
        $output->writeln(\sprintf('Successfully seeded %d products!', \count($sampleProducts)));

        return Command::SUCCESS;
    }
}
