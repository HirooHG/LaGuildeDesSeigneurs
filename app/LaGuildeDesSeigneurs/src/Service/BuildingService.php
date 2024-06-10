<?php

namespace App\Service;

use App\Entity\Building;
use App\Form\BuildingType;
use App\Repository\BuildingRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;

class BuildingService implements BuildingServiceInterface
{
    public function __construct(
        private BuildingRepository $buildingRepository,
        private FormFactoryInterface $formFactoryInterface,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator
    ) {
    }

    public function create(string $data): Building
    {
        $building = new Building();

        $this->submit($building, BuildingType::class, $data);
        $building->setSlug((new Slugify())->slugify($building->getName()));
        $building->setCreation(new \DateTime());
        $building->setModification(new \DateTime());
        $building->setIdentifier(hash("sha1", uniqid()));
        $this->isEntityFilled($building);

        $this->entityManager->persist($building);
        $this->entityManager->flush();

        return $building;
    }

    public function findAll(): array
    {
        return $this->buildingRepository->findAll();
    }

    public function findAllPaginated($query): SlidingPagination
    {
        return $this->paginator->paginate(
            $this->findAll(),
            $query->getInt("page", 1),
            min(100, $query->getInt("size", 10))
        );
    }

    public function update(Building $building, string $data): Building
    {
        $this->submit($building, BuildingType::class, $data);
        $building->setSlug((new Slugify())->slugify($building->getName()));
        $building->setModification(new \DateTime());
        $this->isEntityFilled($building);

        $this->entityManager->persist($building);
        $this->entityManager->flush();

        return $building;
    }

    public function delete(Building $building): void
    {
        $this->entityManager->remove($building);
        $this->entityManager->flush();
    }

    public function submit(Building $building, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        // Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException(
                "Submitted data is not an array -> " . $data
            );
        }
        // Submits form
        $form = $this->formFactoryInterface->create($formName, $building, [
            "csrf_protection" => false,
        ]);
        $form->submit($dataArray, false); // With false, only submitted fields are validated
        // Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg = "Error " . get_class($error->getCause());
            $errorMsg .= " --> " . $error->getMessageTemplate();
            $errorMsg .= " " . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }

    // Checks if the entity has been well filled
    public function isEntityFilled(Building $building)
    {
        // Vérification du bon fonctionnement en introduisant une erreur
        $errors = $this->validator->validate($building);

        if (count($errors) > 0) {
            $errorMsg = (string) $errors . "Wrong data for Entity -> ";
            $errorMsg .= json_encode($this->serializeJson($building));
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (
                $object
            ) {
                return $object->getId(); // Ce qu'il doit retourner
            },
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizers = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer(
            [new DateTimeNormalizer(), $normalizers],
            [$encoders]
        );
        $this->setLinks($object);
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['building'])
            ->toArray();
        return $serializer->serialize($object, "json", $context);
    }

    // Defines the links for HATEOAS
    public function setLinks($object): void
    {
        // Teste si l'objet est une pagination
        if ($object instanceof SlidingPagination) {
            foreach ($object->getItems() as $item) {
                $this->setLinks($item);
            }
            return;
        }

        $identifier = $object->getIdentifier();
        $links = [
            "self" => ["href" => "/buildings/" . $identifier],
            "update" => ["href" => "/buildings/" . $identifier],
            "delete" => ["href" => "/buildings/" . $identifier],
        ];
        $object->setLinks($links);
    }
    public function getImages(int $number): array
    {
        $folder = __DIR__ . '/../../public/images/buildings';
        $finder = new Finder();
        $finder
            ->files() // On veut des fichiers
            ->in($folder) // Dans le dossier images
            ->sortByName() // On trie par nom
        ;
        $images = array();
        foreach ($finder as $file) {
            // dump($file); // Si vous voulez voir le contenu de file
            $images[] = str_replace(__DIR__ . '/../../public', '', $file->getPathname());
        }
        shuffle($images);
        return array_slice($images, 0, $number, true);
    }
}
