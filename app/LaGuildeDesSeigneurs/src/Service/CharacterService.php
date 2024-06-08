<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use App\Event\CharacterEvent;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CharacterType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Cocur\Slugify\Slugify;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactoryInterface,
        private ValidatorInterface $validator,
        private CharacterRepository $characterRepository,
        private EventDispatcherInterface $dispatcher,
        private PaginatorInterface $paginator
    ) {
    }
    public function create(string $data): Character
    {
        $character = new Character();
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);

        $this->submit($character, CharacterType::class, $data);
        $character->setSlug((new Slugify())->slugify($character->getName()));
        $character->setCreation(new DateTime());
        $character->setModification(new DateTime());
        $character->setIdentifier(hash("sha1", uniqid()));
        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function update(Character $character, string $data): Character
    {
        $this->submit($character, CharacterType::class, $data);
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_UPDATED);
        $character->setSlug((new Slugify())->slugify($character->getName()));
        $character->setModification(new DateTime());
        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function delete(Character $character): void
    {
        $this->em->remove($character);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->characterRepository->findAll();
    }

    public function findAllPaginated($query): SlidingPagination
    {
        return $this->paginator->paginate(
            $this->findAll(), // On appelle la même requête
            $query->getInt("page", 1), // 1 par défaut
            min(100, $query->getInt("size", 10)) // 10 par défaut et 100 maximum
        );
    }
    // Submits the form
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        // Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException(
                "Submitted data is not an array -> " . $data
            );
        }
        // Submits form
        $form = $this->formFactoryInterface->create($formName, $character, [
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
    public function isEntityFilled(Character $character)
    {
        // Vérification du bon fonctionnement en introduisant une erreur
        $errors = $this->validator->validate($character);

        if (count($errors) > 0) {
            $errorMsg = (string) $errors . "Wrong data for Entity -> ";
            $errorMsg .= json_encode($this->serializeJson($character));
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    // Serializes the object(s)
    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (
                $object
            ) {
                return $object->getId();
            },
        ];
        $normalizers = new ObjectNormalizer(
            null,
            null,
            null,
            null,
            null,
            null,
            $defaultContext
        );
        $serializer = new Serializer(
            [new DateTimeNormalizer(), $normalizers],
            [$encoders]
        );
        $this->setLinks($object);
        return $serializer->serialize($object, "json");
    }

    public function setLinks($object): void
    {
        // Teste si l'objet est une pagination
        if ($object instanceof SlidingPagination) {
            // Si oui, on boucle sur les items
            foreach ($object->getItems() as $item) {
                $this->setLinks($item);
            }
            return;
        }

        $identifier = $object->getIdentifier();
        $links = [
            "self" => ["href" => "/characters/" . $identifier],
            "update" => ["href" => "/characters/" . $identifier],
            "delete" => ["href" => "/characters/" . $identifier],
        ];
        $object->setLinks($links);
    }

    public function getImages(int $number, ?string $kind = null): array
    {
        $folder = __DIR__ . '/../../public/images/';
        $finder = new Finder();
        $finder
            ->files() // On veut des fichiers
            ->in($folder) // Dans le dossier images
            ->notPath('/buildings/') // On ne veut pas les buildings
            ->sortByName() // On trie par nom
        ;

        if (null !== $kind) {
            $finder->path('/' . $kind . '/');
        }

        $images = array();
        foreach ($finder as $file) {
            // dump($file); // Si vous voulez voir le contenu de file
            $images[] = str_replace(__DIR__ . '/../../public', '', $file->getPathname());
        }
        shuffle($images);
        return array_slice($images, 0, $number, true);
    }

    // Gets random images by kind
    public function getImagesKind(string $kind, int $number): array
    {
        return $this->getImages($number, $kind);
    }
}
