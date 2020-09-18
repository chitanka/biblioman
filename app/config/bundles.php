<?php
return [
	new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
	new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
	new \Symfony\Bundle\TwigBundle\TwigBundle(),
	new \Symfony\Bundle\MonologBundle\MonologBundle(),
	new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
	new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
	new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
	new \EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
	new \Vich\UploaderBundle\VichUploaderBundle(),
	new \BabDev\PagerfantaBundle\BabDevPagerfantaBundle(),
	new \FOS\MessageBundle\FOSMessageBundle(),
	new \Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
	new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
	new \Chitanka\PermissionBundle\ChitankaPermissionBundle(),
	new \Chitanka\WikiBundle\ChitankaWikiBundle(),
	new \App\App(),
];
