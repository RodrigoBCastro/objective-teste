<?php

declare(strict_types=1);

namespace App\Game;

use App\Entity\BinaryTree;
use App\Entity\Node;
use App\Service\NodeService;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class Game
{
    private const yes = "SIM";
    private const no = "NÃO";
    private QuestionHelper $helper;
    private BinaryTree $binarySearch;
    private NodeService $nodeService;
    protected InputInterface $input;
    private OutputInterface $output;

    public function __construct(
        QuestionHelper  $helper,
        BinaryTree      $binarySearch,
        NodeService     $nodeService,
        InputInterface  $input,
        OutputInterface $output
    ) {
        $this->helper = $helper;
        $this->binarySearch = $binarySearch;
        $this->nodeService = $nodeService;
        $this->input = $input;
        $this->output = $output;

        $this->binarySearch->add(null, "Massa", true);
        $this->binarySearch->add($this->binarySearch->root(), "Lasanha", true);
        $this->binarySearch->add($this->binarySearch->root(), "Bolo de Chocolate", false);
    }

    protected function checkCorrectItem(Node $node): string
    {
        $question = new ChoiceQuestion("O prato que você pensou é {$node->getValue()} ?", ["SIM", "NÃO"]);
        $question->setErrorMessage("Resposta Inválida");

        return $this->helper->ask($this->input, $this->output, $question);
    }

    protected function checkString(Question $question): void
    {
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : "";
        });

        $question->setValidator(function ($answer) {
            if (null === $answer) {
                throw new \Exception("Apenas textos");
            }

            return $answer;
        });
    }

    protected function newFood(Node $node): void
    {
        $questionFood = new Question("Qual prato voce pensou ? ");
        $this->checkString($questionFood);

        $food = $this->helper->ask($this->input, $this->output, $questionFood);

        $questionAttribute = new Question("{$food} é ___, mas {$node->getValue()} ? ");
        $this->checkString($questionAttribute);

        $attribute = $this->helper->ask($this->input, $this->output, $questionAttribute);

        $this->nodeService->newNode($node, $attribute, $food);
        $this->bootGame();
    }

    protected function victory(): void
    {
        $this->output->writeln("Acertei de novo !");
        $this->bootGame();
    }

    protected function findAnswer(Node $node): void
    {
        $answer = $this->checkCorrectItem($node);

        if (self::yes === $answer) {
            if ($node->correct()) {
                $this->victory();
            } else {
                $this->findAnswer($node->getRightChild());
            }
        }

        if (self::no === $answer) {
            if (null === $node->getRightChild()) {
                $this->newFood($node);
            } else {
                $this->findAnswer($node->getLeftChild());
            }
        }
    }

    protected function bootGame(): void
    {
        while (true) {
            $this->findAnswer($this->binarySearch->root());
        }
    }

    public function start()
    {
        $this->bootGame();
    }
}
