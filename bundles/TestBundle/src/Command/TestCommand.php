<?php

namespace TestBundle\Command;

use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TestCommand extends AbstractCommand
{
    private $serializer;
    private $mailer;

    public function __construct(SerializerInterface $serializer, MailerInterface $mailer)
    {
        parent::__construct();
        $this->serializer = $serializer;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this->setName('app:test-command')
             ->setDescription('Sends email of serialized objects')
             ->addArgument('email',InputArgument::REQUIRED, 'Email address to send');
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');

        $objects = DataObject\TestDataObject1::getList();

        if (count($objects) === 0) {
            $output->writeln('Обьекты не найдены');
            return self::SUCCESS;
        }

//        $output->writeln($objects);

        try {
            $serializedData = $this->serializer->serialize($objects,'json');
        } catch (\Exception $e) {
            $output->writeln('Error during serialization: ' . $e->getMessage());
            return self::FAILURE;
        }


        $output->writeln('Отправляю письмо');
        $this->sendEmail($email,$serializedData);

        $output->writeln('Письмо отправленно');

        return self::SUCCESS;
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function sendEmail(string $email, string $serializedData)
    {
        $emailMessage = (new Email())->from('noreply@test.com')->to($email)->subject('Serialized objects')->attach($serializedData,'objects.json','application/json');

        $this->mailer->send($emailMessage);
    }
}
