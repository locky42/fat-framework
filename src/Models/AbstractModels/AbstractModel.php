<?php

namespace App\Models\AbstractModels;

use PDO;
use Exception;
use App\Helpers\PdoHelper;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

abstract class AbstractModel
{
    protected static $tableName = null;

    protected $db;

    public function __construct()
    {
        $this->db = PdoHelper::getPdo();
    }

    public function create(array $data)
    {
        $this->isAllowedAction("create");
        $this->db->beginTransaction();
        try {
            // Prepare an SQL INSERT statement
            $fields = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $stmt = $this->db->prepare('INSERT INTO ' . static::getTableName() . " ($fields) VALUES ($placeholders)");
    
            // Bind the values and execute the statement
            foreach ($data as $key => &$value) {
                $stmt->bindParam(':' . $key, $value);
            }
            $stmt->execute();
    
            $this->db->commit();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data)
    {
        $this->isAllowedAction('update');
        $this->db->beginTransaction();
        try {
            // Prepare an SQL UPDATE statement
            $fields = array_keys($data);
            $placeholders = ':' . implode(', :', array_keys($data));
            $stmt = $this->db->prepare('UPDATE ' . static::getTableName() . " SET $fields = $placeholders WHERE id = :id");
    
            // Bind the values and execute the statement
            foreach ($data as $key => &$value) {
                $stmt->bindParam(':' . $key, $value);
            }
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $this->isAllowedAction('delete');
        $this->db->beginTransaction();
        try {
            // Prepare an SQL DELETE statement
            $stmt = $this->db->prepare('DELETE FROM ' . static::getTableName() . " WHERE id = :id");
    
            // Bind the values and execute the statement
            $stmt->bindParam(':id', $id);
            $stmt->execute();
    
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAll()
    {
        $this->isAllowedAction('list');
        $stmt = $this->db->prepare('SELECT * FROM ' . static::getTableName());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $this->isAllowedAction("get");
        $stmt = $this->db->prepare('SELECT * FROM ' . static::getTableName() . " WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    protected function isAllowedAction($action = null)
    {
        return true;
    }

    public static function getTableName()
    {
        if (!static::$tableName) {
            $reader = new AnnotationReader();

            // Get the class annotation
            $reflectionClass = new ReflectionClass(static::class);
            $classAnnotations = $reader->getClassAnnotations($reflectionClass);
            
            // Find the @Table annotation and return the name
            foreach ($classAnnotations as $annotation) {
                if ($annotation instanceof \Doctrine\ORM\Mapping\Table) {
                    static::$tableName = $annotation->name;
                    return $annotation->name;
                }
            }

            throw new Exception('Table annotation not found in ' . static::class);
        }

        return static::$tableName;
    }
}
