<?php
return [
	new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
	new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
	new \Symfony\Bundle\TwigBundle\TwigBundle(),
	new \Symfony\Bundle\MonologBundle\MonologBundle(),
	new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
	new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
	new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
	new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
	new \EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
	new \Vich\UploaderBundle\VichUploaderBundle(),
	new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
	new \FOS\MessageBundle\FOSMessageBundle(),
	new \Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
	new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
	new \Chitanka\WikiBundle\ChitankaWikiBundle(),
	new \App\App(),
];
