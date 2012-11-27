<?php

namespace Lexik\Bundle\MailerBundle\Tests\Fixtures;

use Lexik\Bundle\MailerBundle\Entity\Layout;
use Lexik\Bundle\MailerBundle\Entity\LayoutTranslation;
use Lexik\Bundle\MailerBundle\Entity\Email;
use Lexik\Bundle\MailerBundle\Entity\EmailTranslation;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TestData implements FixtureInterface
{
    /**
     * @see Doctrine\Common\DataFixtures.FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
        // layouts
        $translations = array(
            array(
                'locale' => 'fr',
                'body' => 'blablabla {{title}} {% block content %}{% endblock %} blablabla',
            ),
            array(
                'locale' => 'en',
                'body' => 'blublublu {{title}} {% block content %}{% endblock %} blublublu',
            ),
            array(
                'locale' => 'es_ES',
                'body' => 'bliblibli {{title}} {% block content %}{% endblock %} bliblibli',
            ),
        );

        $layout = new Layout();
        $layout->setDescription('super layout');
        $layout->setReference('rabbid-layout');

        foreach ($translations as $trans) {
            $translation = new LayoutTranslation();
            $translation->setBody($trans['body']);
            $translation->setLang($trans['locale']);

            $layout->addTranslation($translation);
        }

        $manager->persist($layout);
        $manager->flush();

        // emails
        $translations = array(
            array(
                'locale' => 'fr',
                'subject' => 'lapin crétins',
                'body' => '{{name}} aime les lapins crétins.',
                'from_address' => 'lapins@email.fr',
                'from_name' => 'lapin',
            ),
            array(
                'locale' => 'en',
                'subject' => 'raving rabbids',
                'body' => '{{name}} likes raving rabbids.',
                'from_address' => 'rabbids@email.fr',
                'from_name' => 'rabbid',
            ),
            array(
                'locale' => 'es_ES',
                'subject' => 'this template won\'t work',
                'body' => '{{name} <-- fail',
                'from_address' => 'rabbids@email.fr',
                'from_name' => 'rabbid',
            ),
        );

        $email = new Email();
        $email->setBcc('one@email.fr; two@email.fr');
        $email->setDescription('bwah!');
        $email->setReference('rabbids-template');
        $email->setSpool(false);
        $email->setLayout($layout);

        foreach ($translations as $trans) {
            $translation = new EmailTranslation();
            $translation->setLang($trans['locale']);
            $translation->setSubject($trans['subject']);
            $translation->setBody($trans['body']);
            $translation->setFromAddress($trans['from_address']);
            $translation->setFromName($trans['from_name']);

            $email->addTranslation($translation);
        }

        $manager->persist($email);
        $manager->flush();
    }
}