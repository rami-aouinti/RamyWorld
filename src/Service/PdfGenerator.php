<?php

namespace App\Service;

use App\Entity\Profile;
use App\Entity\Skill;
use Imagick;
use setasign\Fpdi\Fpdi;
use Fpdf;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Pdf Service
 */
class PdfGenerator extends Fpdi
{
    public function generate($filename, array $data)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile('resume_template_pink.pdf');
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);

            /* add a page */
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, null, null);

            if($pageNo == 1) {
                /* font and color selection */
                $pdf->SetFont('Helvetica');
                $pdf->SetTextColor(200, 0, 0);
                $this->addLogo($pdf);
                $this->setHeader($pdf, $data['profile']);
                $this->setContact($pdf, $data['profile']);
                $pdf->Ln();
                $this->setSkills($pdf, $data['skills']);
                $pdf->Ln();
                $this->setProfile($pdf, $data['profile']);
                $pdf->Ln();
                $this->setEducations($pdf, $data['educations']);
                $pdf->Ln();
                $this->setExperiences($pdf, $data['experiences']);
            } else {
                $pdf->Ln();
                $this->setLanguages($pdf, $data['languages']);
                $pdf->Ln();
                $this->setHobbies($pdf, $data['hobbies']);
                $this->setProjects($pdf, $data['projects']);
            }
        }

        $pdf->Output();
        $pdf->Output($filename.".pdf",'D'); // This will output the PDF as a response directly
    }

    private function setProfile($pdf, Profile $profile) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setXY(70, 50);
        $pdf->Write(12, 'About me');
        $pdf->Ln();
        $pdf->setFontSize(12);
        $pdf->setXY(70,50);
        $pdf->MultiCell(120,7,$profile->getDescription(),10,1,'');
    }

    private function setEducations($pdf, array $educations) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setX(70);
        $pdf->Write(12, 'Education');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($educations as $education)
        {
            $pdf->setX(70);
            $pdf->Write(12, $education->getStartDate()->format('M.Y') . ' - ' . $education->getEndDate()->format('M.Y'));
            $pdf->Ln();
            $pdf->setX(70);
            $pdf->MultiCell(120, 7,  $education->getGrad() . ' ' . $education->getSchool());
            $pdf->Ln();
            $pdf->setX(70);
            $pdf->MultiCell(120, 7,  $education->getDescription());
            $pdf->Ln();
        }
    }
    private function setExperiences($pdf, array $experiences) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setX(70);
        $pdf->Write(12, 'Experience');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($experiences as $experience)
        {
            $pdf->setX(70);
            $pdf->Write(12, $experience->getCompany());
            $pdf->Ln();
        }
    }
    private function setProjects($pdf, array $projects) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setXY(70, 20);
        $pdf->Write(12, 'Projects');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($projects as $project)
        {
            $pdf->setX(70);
            $pdf->Write(12, $project->getName());
            $pdf->Ln();
        }
    }

    private function setSkills($pdf, array $skills) {
        $pdf->Ln();
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setX(2);
        $pdf->Write(12, 'Skills');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($skills as $skill)
        {
            $pdf->setX(5);
            $pdf->Write(12, $skill->getName());
            for ($i = 0; $i < $skill->getLevel(); $i++)
            {
                $pdf->Image('stars.png',$pdf->getX() + 3 +($i *5) ,$pdf->getY() + 4, 5);
            }
            $pdf->Ln();
        }
    }
    private function setLanguages($pdf, array $languages) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setX(2);
        $pdf->Write(12, 'Languages');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($languages as $language)
        {
            $pdf->setX(5);
            $pdf->Write(12, $language->getName());
            for ($i = 0; $i < $language->getLevel(); $i++)
            {
                $pdf->Image('stars.png',$pdf->getX() + 3 +($i *5) ,$pdf->getY() + 4, 5);
            }
            $pdf->Ln();
        }
    }
    private function setHobbies($pdf, array $hobbies) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setX(2);
        $pdf->Write(12, 'Hobbies');
        $pdf->Ln();
        $pdf->setFontSize(12);
        foreach ($hobbies as $hobby)
        {
            $pdf->setX(5);
            $pdf->Write(12, $hobby->getName());
            $pdf->Ln();
        }
    }


    private function setContact($pdf, Profile $profile) {
        $pdf->SetFont('Helvetica');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(18);
        $pdf->setXY(2, 80);
        $pdf->Write(12, 'Contact');
        $pdf->Ln();
        $pdf->setX(10);
        $pdf->setFontSize(12);
        $pdf->Image('phone.png',5 ,95,4);
        $pdf->Write(12, $profile->getMobile());
        $pdf->Ln();
        $pdf->Image('mail.png',5 ,108,4);
        $pdf->Write(12, 'rami.aouinti@gmail.com');
        $pdf->Ln();
        $pdf->Image('maps.png',5 ,119,4);
        $pdf->Write(12, $profile->getState() . ', ' . $profile->getCountry());
        $pdf->Ln();
        $pdf->Image('linkedin.png',5 ,132,4);
        $pdf->Cell(12,11 ,$profile->getFirstname()  . ' ' . $profile->getLastname() ,'','','',false, "https://www.linkedin.com/in/rami-aouinti-78998a59/");
    }

    private function setHeader($pdf,Profile $profile)
    {
        $pdf->SetFont('Helvetica', 'I');
        $pdf->setTextColor(0, 0, 0);
        $pdf->setFontSize(32);
        $pdf->setX(70);
        $pdf->Write(12, $profile->getFirstname()  . ' ' . $profile->getLastname());
        $pdf->Ln();
        $pdf->setX(70);
        $pdf->setFontSize(22);
        $pdf->Write(12, $profile->getTitle());
    }


    private function addLogo($pdf) {
        /* Logo */
        $pdf->Image('profile.jpg',9,25,50);
    }
}
