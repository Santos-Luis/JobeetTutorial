<?php

namespace App\Command;

use App\Service\CategoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateCategoryCommand extends Command
{
    /** @var CategoryService */
    private $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('app:create-category')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the category.')
            ->setDescription('Creates a new category')
            ->setHelp('This command allows you to add a new category in the db...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categoryName = $input->getArgument('name');

        $this->categoryService->create($categoryName);

        $output->writeln(sprintf('<fg=green>Category %s successfully created!</>', $input->getArgument('name')));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('<question>Please choose a name:</question> ');
            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new \Exception('Name can not be empty');
                }

                return $name;
            });

            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('name', $answer);
        }
    }
}
