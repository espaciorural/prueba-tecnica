<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\QuestionHelper;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
     public function __construct(QuestionHelper $questionHelper)
     {
          $this->questionHelper = $questionHelper;
     }

     /**
      * @Route("/questions", name="app_questions")
      */


    
      public function questions(Request $request): Response
      {
          $url = 'https://api.stackexchange.com/2.3/questions?site=stackoverflow';
  
          $curl = curl_init();
          curl_setopt_array($curl, [
              CURLOPT_URL => $url,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                ),
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_SSL_VERIFYHOST => false
          ]);

          $rawResponse = curl_exec($curl);
          $info = curl_getinfo($curl);
          curl_close($curl);

          if ($info['http_code'] === 200) {
               $response = json_decode($rawResponse, true);
               $questions = $response['items'];
          }

          $task = new Task();
          $task->setTag('Write tags');
          $task->setFromDate(new \DateTime('yesterday'));
          $task->setToDate(new \DateTime('today'));
          $form = $this->createFormBuilder($task)
          ->add('tag', TextType::class)
          ->add('fromDate', DateType::class)
          ->add('toDate', DateType::class)
          ->add('search', SubmitType::class, ['label' => 'Search questions'])
          ->getForm();

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
              $task = $form->getData();
              $fromDate = $task->fromDate->getTimestamp();
              $toDate = $task->toDate->getTimestamp();
              $q = array();
              if (!empty($questions)) {
                foreach($questions as $item) {
                    if ($fromDate<$item["creation_date"] && $toDate>$item["creation_date"]) {
                        foreach ($item['tags'] as $qst) {
                            $query = strtolower($task->task);
                            if ($qst == $query) {
                                $q[] = $item;
                            }
                        }
                    }
                }
              }
              $questions = $q;
          }

  
          return $this->render('questions/questions.html.twig', [
              'questions' => $questions ?? [],
              'form' => $form->createView()      ]);
      }
}