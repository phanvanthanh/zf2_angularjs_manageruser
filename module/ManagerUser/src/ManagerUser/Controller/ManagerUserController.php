<?php

namespace ManagerUser\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
    
use Zend\View\Model\JsonModel;
use Application\Entity\SystemUser;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Crypt\Password\Bcrypt;

class ManagerUserController extends AbstractRestfulController
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
        $user = new SystemUser();

        $bcrypt = new Bcrypt(); // tạo mật khẩu
        $bcrypt->setCost(14);
        $data['password'] = $bcrypt->create($data['password']);
        
        $user = $hydrator->hydrate($data, $user); // chuyển mảng $data thành đối tượng        
        $entityManager->persist($user);
        $entityManager->flush();
        $data=array();
        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $entityManager=$this->getEntityManager();
        $hydrator = new DoctrineHydrator($entityManager);
        $user = $entityManager->getRepository('Application\Entity\SystemUser')->find($id);
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $password = $data['password'];            
        $user->setPassword ($bcrypt->create($password));
        $user->setUsername($data['username']);
        $entityManager->flush();
                
        $user=$hydrator->extract($user); // giải nén object user thành array user
        return new JsonModel(array(
            'data' => $user,
        ));
    }

    public function delete($id)
    {
        $entityManager=$this->getEntityManager();
        $user=$entityManager->getRepository('Application\Entity\SystemUser')->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }
}
