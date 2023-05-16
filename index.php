<?php

require_once 'Models.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$pathParts = explode('/', $path);
$resourceType = $pathParts[1] ?? '';

$allowedToken = 'my_secret_token';

$token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

if ($token !== 'Bearer' . $allowedToken) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($resourceType === 'items' && $method === 'GET') {
  $items = (new Item())->getAll();
  echo json_encode($items);
  exit;
}

if ($resourceType === 'items' && $method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $item = new Item();
  $item->create($data);
  echo json_encode(['id' => $item->getLastInsertedId()]);
  exit;
}

if ($resourceType === 'items' && $method === 'DELETE') {
  $id = $pathParts[2] ?? '';
  if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Item ID is missing']);
    exit;
  }
  $item = new Item();
  $item->delete($id);
  echo json_encode(['success' => true]);
  exit;
}

if ($resourceType === 'items' && $method === 'PUT') {
  $id = $pathParts[2] ?? '';
  if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Item ID is missing']);
    exit;
  }
  $data = json_decode(file_get_contents('php://input'), true);
  $item = new Item();
  $item->update($id, $data);
  echo json_encode(['success' => true]);
  exit;
}

if ($resourceType === 'items' && $method === 'GET' && isset($pathParts[2]) && $pathParts[2] === 'history') {
  $id = $pathParts[3] ?? '';
  if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Item ID is missing']);
    exit;
  }
  $itemHistory = (new ItemHistory())->getAllByItemId($id);
  echo json_encode($itemHistory);
  exit;
}

$app->post('/items/{id}/history', function ($request, $response, $args) use ($container) {
    $data = $request->getParsedBody();
    $itemHistoryModel = $container->get('item_history_model');
    $itemHistoryModel->create(array_merge(['item_id' => $args['id']], $data));
    return $response->withStatus(201);
    });
    
    $app->get('/items', function ($request, $response) use ($container) {
    $itemModel = $container->get('item_model');
    $items = $itemModel->getAll();
    return $response->withJson($items);
    });
    
    $app->get('/items/{id}', function ($request, $response, $args) use ($container) {
    $itemModel = $container->get('item_model');
    $item = $itemModel->getById($args['id']);
    if (!$item) {
    return $response->withStatus(404);
    }
    return $response->withJson($item);
    });
    
    $app->post('/items', function ($request, $response) use ($container) {
    $data = $request->getParsedBody();
    $itemModel = $container->get('item_model');
    $item = $itemModel->create($data);
    return $response->withJson($item, 201);
    });
    
    $app->delete('/items/{id}', function ($request, $response, $args) use ($container) {
    $itemModel = $container->get('item_model');
    $success = $itemModel->delete($args['id']);
    if (!$success) {
    return $response->withStatus(404);
    }
    return $response->withStatus(204);
    });