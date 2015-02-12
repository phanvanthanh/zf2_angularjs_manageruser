<?php

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\Controller\AbstractActionController;
    
use Zend\View\Model\JsonModel;
use Application\Entity\SystemUser;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Crypt\Password\Bcrypt;

class LoginController extends AbstractRestfulController
{
    private $entityManager;
  
    public function getEntityManager()
    {
     if(!$this->entityManager)
     {
      $this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
     }
     return $this->entityManager;
    }
    
    public function getList()
    {
        $entityManager=$this->getEntityManager();
        $results=$entityManager->getRepository('Application\Entity\SystemUser')->findAll();

        $data = array();
        foreach($results as $result) {
            $hydrator = new DoctrineHydrator($entityManager);
            $dataArray = $hydrator->extract($result); // chuyển từ đối tượng sang mảng
            $data[] = $dataArray;
        }
        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function deleteList(){
        
    }

    public function get($id)
    {
        $entityManager=$this->getEntityManager();
        $result=$entityManager->getRepository('Application\Entity\SystemUser')->find($id);
        $hydrator = new DoctrineHydrator($entityManager);        
        $user=$hydrator->extract($result);// chuyển từ đối tượng sang mảng
        return new JsonModel(array(
            'data' => $user,
        ));
    }

    public function create($data)
    {
        $entityManager=$this->getEntityManager(); //khai báo đối tượng cần sử dụng
        $hydrator = new DoctrineHydrator($entityManager);
        $bcrypt = new Bcrypt(); // tạo mật khẩu
        $bcrypt->setCost(14);
        $username=$data['username'];
        $password = $bcrypt->create($data['password']);
        $qb=$entityManager->createQueryBuilder();
        $qb->select('u');
        $qb->from('Application\Entity\SystemUser', 'u');
        $qb->andwhere('u.username=\''.$username.'\'');
        //$qb->where('u.password=\''.$password.'\'');
        $query = $qb->getQuery();
        $users = $query->getResult();
        $data='error';
        if($users)
        {
            $user=$users[0];
            $user=$hydrator->extract($user); // giải nén object user thành array user
            $data='success';
        }            

        return new JsonModel(array(
            'data' => $data
        ));
    }
    
}
