<?php
namespace App\Core;

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->table = $this->getTable();
        
        // 应用表前缀
        $config = require __DIR__ . '/../../config/database.php';
        if (!empty($config['prefix'])) {
            $this->table = $config['prefix'] . $this->table;
        }
    }
    
    /**
     * 获取表名
     * @return string 表名
     */
    protected function getTable() {
        $className = get_class($this);
        $modelName = substr($className, strrpos($className, '\\') + 1);
        return strtolower($modelName) . 's';
    }
    
    /**
     * 获取所有记录
     * @return array 所有记录
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * 根据ID获取记录
     * @param int $id 记录ID
     * @return array 记录信息
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * 创建记录
     * @param array $data 记录数据
     * @return int|false 新创建记录的ID或false
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            error_log("Model create failed: Failed to prepare SQL statement");
            return false;
        }
        
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        } else {
            error_log("Model create failed: Failed to execute SQL statement");
            return false;
        }
    }
    
    /**
     * 更新记录
     * @param int $id 记录ID
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function update($id, $data) {
        $setClause = '';
        foreach (array_keys($data) as $key) {
            $setClause .= "{$key} = :{$key}, ";
        }
        $setClause = rtrim($setClause, ', ');
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * 删除记录
     * @param int $id 记录ID
     * @return bool 是否删除成功
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
