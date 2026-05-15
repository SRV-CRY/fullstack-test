<?php

namespace App\Controllers;

use App\Models\Comment;

class Comments extends BaseController
{
    public function index()
    {
        $model = new Comment();
        
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'desc';
        
        $allowedSort = ['id', 'created_at'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'created_at';
        }
        
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($order, $allowedOrder)) {
            $order = 'desc';
        }
        
        $comments = $model->orderBy($sort, $order)->paginate(3);
        $pager = $model->pager;
        
        if ($this->request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') {
            return $this->response->setJSON([
                'comments' => view('comments/list', ['comments' => $comments]),
                'pagination' => $pager->links()
            ]);
        }
        
        return view('comments/index', [
            'comments' => $comments,
            'pager' => $pager,
            'sort' => $sort,
            'order' => $order
        ]);
    }
    
    public function store()
    {
        $model = new Comment();
        
        $data = [
            'name' => $this->request->getPost('name'),
            'text' => $this->request->getPost('text'),
            'date' => $this->request->getPost('date')
        ];
        
        if (!$model->validate($data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $model->errors()
            ]);
        }
        
        $model->save($data);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Комментарий добавлен'
        ]);
    }
    
    public function delete($id)
    {
        $model = new Comment();
        $model->delete($id);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Комментарий удален'
        ]);
    }
}
