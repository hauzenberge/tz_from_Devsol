<?php

require_once 'Model.php';

class Item extends Model
{
    protected $historyModel;

    public function __construct()
    {
        parent::__construct('items');
        $this->historyModel = new ItemHistory();
    }

    private function validateData($data)
    {
        $errors = [];

        if (!isset($data['name']) || !is_string($data['name']) || strlen($data['name']) > 255) {
            $errors[] = 'Invalid or missing name';
        }

        if (!isset($data['phone']) || !is_string($data['phone']) || strlen($data['phone']) > 15) {
            $errors[] = 'Invalid or missing phone';
        }

        if (!isset($data['item_key']) || !is_string($data['item_key']) || strlen($data['item_key']) > 25) {
            $errors[] = 'Invalid or missing item_key';
        }

        return $errors;
    }

    public function create($data)
    {
        $errors = $this->validateData($data);
        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        parent::create($data);

        $item = parent::create($data);
        return $item;
    }

    public function update($id, $data)
    {
        parent::update($id, $data);
        $data['item_id'] = $id;
        $this->historyModel->create($data);
    }
}

class ItemHistory extends Model
{
    public function __construct()
    {
        parent::__construct('item_history');
    }

    public function create($data)
    {
        parent::create($data);
    }
}
