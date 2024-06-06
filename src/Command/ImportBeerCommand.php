<?php

namespace App\Command;

use App\Entity\Beer;
use App\Entity\BeerCategory;
use App\Entity\BeerStyle;
use App\Entity\Brewery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:beer',
    description: 'Import beers from csv file'
)]
class ImportBeerCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $csvBeersToAdd = $this->getBeerCsvFileAsArray('open-beer-database.csv');

        $beerAddCounter = 0;
        foreach ($csvBeersToAdd as $beerToAdd) {
            if (!intval($beerToAdd['id'])) {
                continue;
            }

            if ($this->entityManager->getRepository(Beer::class)->find($beerToAdd['id'])) {
                continue;
            }

            $brewery = $this->getBeerBrewery($beerToAdd);
            $beerCategory = $this->getBeerCategory($beerToAdd);
            $beerStyle = $this->getBeerStyle($beerToAdd);

            $beer = new Beer();
            $beer->setId($beerToAdd['id']);
            $beer->setName($beerToAdd['Name']);
            $beer->setAbv($beerToAdd['Alcohol By Volume']);
            $beer->setBrewery($brewery);
            $beer->setCategory($beerCategory);
            $beer->setStyle($beerStyle);
            $beer->setIbu($beerToAdd['International Bitterness Units']);
            $beer->setDescription($beerToAdd['Description']);
            $beer->setSrm($beerToAdd['Standard Reference Method']);
            $beer->setUpc($beerToAdd['Universal Product Code']);

            $this->entityManager->persist($beer);
            $output->writeln('Creating Beer "'.$beer->getName().'"');
            $beerAddCounter++;

            if ($beerAddCounter % 500 === 0) {
                $output->writeln('Flushing ...');
                $this->entityManager->flush();
            }
        }

        $output->writeln('Flushing ...');
        $this->entityManager->flush();

        $output->writeln('Successfully created '.$beerAddCounter.' beers');
        return Command::SUCCESS;
    }

    private function getBeerCsvFileAsArray(string $filepath): array
    {
        $file = file($filepath, FILE_SKIP_EMPTY_LINES);
        $csv = array_map("str_getcsv",$file, array_fill(0, count($file), ';'));

        $keys = array_shift($csv);

        foreach ($csv as $i => $row) {
            if (count($keys) === count($row)) {
                $csv[$i] = array_combine($keys, $row);
            } else {
                unset($csv[$i]);
            }
        }

        return $csv;
    }

    private function getBeerBrewery(array $beer): ?Brewery
    {
        if (intval($beer['brewery_id']) <= 0) {
            return null;
        }

        $brewery = $this->entityManager->getRepository(Brewery::class)->find($beer['brewery_id']);

        if (!$brewery) {
            $newBrewery = new Brewery();
            $newBrewery->setId($beer['brewery_id']);
            $newBrewery->setName($beer['Brewer']);
            $newBrewery->setCity($beer['City']);
            $newBrewery->setCountry($beer['Country']);
            $newBrewery->setStreet($beer['Address']);
            $newBrewery->setWebsite($beer['Website']);

            $coordinates = explode(',', $beer['Coordinates']);

            if (count($coordinates) === 2) {
                $newBrewery->setLatitude($coordinates[0]);
                $newBrewery->setLongitude($coordinates[1]);
            }

            $this->entityManager->persist($newBrewery);
            //$this->entityManager->flush();

            $brewery = $newBrewery;
        }

        return $brewery;
    }

    private function getBeerStyle(array $beer): ?BeerStyle
    {
        if (intval($beer['style_id']) <= 0) {
            return null;
        }

        $beerStyle = $this->entityManager->getRepository(BeerStyle::class)->find($beer['style_id']);

        if (!$beerStyle) {
            $newBeerStyle = new BeerStyle();
            $newBeerStyle->setId($beer['style_id']);
            $newBeerStyle->setName($beer['Style']);

            $this->entityManager->persist($newBeerStyle);
            //$this->entityManager->flush();

            $beerStyle = $newBeerStyle;
        }

        return $beerStyle;
    }

    private function getBeerCategory(array $beer): ?BeerCategory
    {
        if (intval($beer['cat_id']) <= 0) {
            return null;
        }

        $beerCategory = $this->entityManager->getRepository(BeerCategory::class)->find($beer['cat_id']);

        if (!$beerCategory) {
            $newBeerCategory = new BeerCategory();
            $newBeerCategory->setId($beer['cat_id']);
            $newBeerCategory->setName($beer['Category']);

            $this->entityManager->persist($newBeerCategory);
            //$this->entityManager->flush();

            $beerCategory = $newBeerCategory;
        }

        return $beerCategory;
    }
}