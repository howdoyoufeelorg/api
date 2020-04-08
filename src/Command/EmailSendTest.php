<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 08/04/2020
 * Time: 10:56 am
 */

namespace App\Command;


use App\Messenger\EmailNotification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailSendTest extends Command
{
    public static $defaultName = 'api:email_send_test';
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('emails', InputArgument::IS_ARRAY, 'Who should receive the test email? (space separated list)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emails = $input->getArgument('emails');
        $invitationSubject = 'HowDoYouFeel emailing TEST';
        $invitationText = 'THIS IS A TEST';
        foreach($emails as $email) {
            $invite = new EmailNotification('info@howdoyoufeel.org', $email, $invitationSubject, $invitationText);
            $this->messageBus->dispatch($invite);
        }
        $output->writeln('DONE');
    }
}