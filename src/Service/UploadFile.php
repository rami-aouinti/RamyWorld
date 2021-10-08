<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * Class UploadFile
 */
class UploadFile
{

    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params)
    {
        $this->slugger = $slugger;
        $this->params = $params;
    }

    /**
     * @param UploadedFile $attr
     * @param string $path
     * @return string
     */
    public function upload(UploadedFile $attr, string $path)
    {
        if ($attr) {
            $originalFilename = pathinfo($attr->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$attr->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $attr->move(
                    $this->params->get($path),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            return $newFilename;
        }
    }
}
