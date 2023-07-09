<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Estimate;
use App\Entity\Invoice;
use App\Entity\EstimatePrestation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ReminderMailEstimateCommand extends Command
{
    protected static $defaultName = 'app:reminder-mail';
    private $entityManager;

    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add quantity to a product if date is expired')
            ->setHelp('This command adds quantity to a product and set Status refused.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $date = new \DateTime();
        $estimates = $this->entityManager->getRepository(Estimate::class)->getEstimateByDateValidity($date);

        foreach($estimates as $estimate){
            $customer = $estimate->getCustomer();
            if($customer->getIsRegistered()){
                $email = (new TemplatedEmail())
                    ->from("zaidmouhamad@gmail.com")
                    ->to($customer->getEmail())
                    ->subject('Devis en attente')
                    ->htmlTemplate('back/email/remindEstimate.html.twig')
                    ->context([
                        'date' => $estimate->getValidityDate(),
                    ]);
                $this->mailer->send($email);
            }
        }

    
        return Command::SUCCESS;
    }
}
