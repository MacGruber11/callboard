<?php

class ApiController extends Controller {

    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    /**
     * @return array action filters
     */
    public function filters() {
        return array();
    }

    // Actions
    public function actionList() {
        // Get the respective model instance
        switch ($_GET['model']) {
            case 'item':
                $models = Item::model()->findAll();
                break;
            default:
                // Model not implemented error
                $this->_sendResponse(501, sprintf(
                                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Did we get some results?
        if (empty($models)) {
            // No
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        } else {
            // Prepare response
            $rows = array();
            foreach ($models as $model)
                $rows[] = $model->attributes;
            // Send the response
            $this->_sendResponse(200, CJSON::encode($rows));
        }
    }

    public function actionView() {

        // Check if id was submitted via GET
        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        switch ($_GET['model']) {
            // Find respective model    
            case 'item':
                $model = Item::model()->findByPk($_GET['id']);
                break;
            case 'user':
                $model = User::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf(
                                'Mode <b>view</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if (is_null($model))
            $this->_sendResponse(404, 'No Item found with id ' . $_GET['id']);
        else
            $this->_sendResponse(200, CJSON::encode($model));
    }

    public function actionCreate() {
        $this->_checkAuth();
        switch ($_GET['model']) {
            // Get an instance of the respective model
            case 'item':
                $model = new Item;
                break;
            default:
                $this->_sendResponse(501, sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Try to assign POST values to attributes

        $model->name = $_POST['name'];
        $model->price = $_POST['price'];
        $model->image = $_FILES['image']['name'];

        // Try to save the model

        if ($model->save()) {
            if ($model->image) {
                move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/images/orig/' . $model->id . '.jpg');
                $file = $_SERVER['DOCUMENT_ROOT'] . '/images/orig/' . $model->id . '.jpg';
                $ih = new CImageHandler();
                Yii::app()->ih
                        ->load($file)
                        ->thumb('200', '200')
                        ->save('./images/small/' .
                                $model->id . '.jpg')
                        ->reload()
                        ->thumb('600', '800')
                        ->save('./images/main/' . $model->id . '.jpg');
            }
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    public function actionUpdate() {
        $this->_checkAuth();
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        switch ($_GET['model']) {
            case 'item':
                $model = Item::model()->findByPk($_GET['id']);
                if ((Yii::app()->user->id) != $model->user_id) {
                    $this->_sendResponse(501, sprintf('Error: This not your item', $_GET['model']));
                    Yii::app()->end();
                }
                $this->updateItem($model);
                break;
            default:
                $this->_sendResponse(501, sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
    }

    public function updateItem($model) {
        if ($model === null)
            $this->_sendResponse(400, sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));

        $model->name = $_POST['name'];
        $model->price = $_POST['price'];
        $model->image = $_FILES['image']['name'];
        if (!isset($model->image)) {
            $model->image = $model->id . '.jpg';
            $model->save();
            $this->_sendResponse(200, CJSON::encode($model));
            return;
        }
        if ($model->save()) {
            if ($model->image) {
                move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/images/orig/' . $model->id . '.jpg');
                $file = $_SERVER['DOCUMENT_ROOT'] . '/images/orig/' . $model->id . '.jpg';
                $ih = new CImageHandler();
                Yii::app()->ih
                        ->load($file)
                        ->thumb('200', '200')
                        ->save('./images/small/' .
                                $model->id . '.jpg')
                        ->reload()
                        ->thumb('600', '800')
                        ->save('./images/main/' . $model->id . '.jpg');
            }
            $this->_sendResponse(200, CJSON::encode($model));
        } else
            $this->_sendResponse(500, $msg);
    }

    public function actionProfileUpdate($model) {
        $this->_checkAuth();
        $model = User::model()->findByPk(Yii::app()->user->id);
        foreach ($_POST as $var => $value) {
            if ($model->attributes)
                $model->$var = $value;
            else {
                $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
            }
        }

        if ($model->save())
            $this->_sendResponse(200, CJSON::encode($model));
        else
            $this->_sendResponse(500, $msg);
    }

    public function actionDelete() {
        $this->_checkAuth();
        $condition = ('user_id = :user_id');
        $params = array('user_id' => Yii::app()->user->id);
        switch ($_GET['model']) {
            // Load the respective model
            case 'item':
                $model = new Item;
                break;
            default:
                $this->_sendResponse(501, sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }

        $num = $model->deleteByPk($_GET['id'], $condition, $params);
        if ($num > 0)
            $this->_sendResponse(200, $num);    //this is the only way to work with backbone
        else
            $this->_sendResponse(500, sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));
    }

    public function actionAuth() {
        if (Yii::app()->user->isGuest) {
            $model = new UserLogin;
            // collect user input data
            foreach ($_POST as $var => $value) {
                // Does the model have this attribute? If not raise an error
                if ($model->attributes)
                    $model->$var = $value;
                else
                    $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
            }
            // validate user input and redirect to previous page if valid
            if ($model->validate()) {
                $this->lastViset();
                echo 'You auth is ok';
            }
        } else
            $this->_sendResponse(500, sprintf("Error: Your auth already now"));
    }

    private function lastViset() {
        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
        $lastVisit->lastvisit_at = date('Y-m-d H:i:s');
        $lastVisit->save();
    }

    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
        }
        // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on 
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status) {
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    private function _checkAuth() {
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if (Yii::app()->user->isGuest) {
            $this->_sendResponse(401);
            Yii::app()->end();
        }
    }

}
