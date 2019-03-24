<?php

//Registrar
function  register($correo, $pass,$usuario = null, $verifica = false, $extra = []){
    global $tishdb, $auth, $token;
    try {
        if($verifica){
            $id = $auth->register($correo, $pass, $usuario, function ($selector, $token) {
                $GLOBALS['token'] = urlencode(base64_encode(json_encode(compact('selector', 'token'))));
            });
        } else {
            $id = $auth->register($correo, $pass, $usuario);
        }
        /*if(count($extra)){
            $tishdb->update('users', $extra, compact('id'));
        } else {
            $tishdb->update('users', compact('role'), compact('id'));
        }*/
        return ($verifica) ? array('error' => FALSE, 'id'=>$id, 'token' => $token) : array('error' => FALSE, 'id' => $id);
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'type' => 'correo', 'message' => 'El correo es incorrecto');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'type' => 'contraseña', 'message' => 'La contraseña es incorrecta');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        return array('error' => TRUE, 'type' => 'usuario_existente', 'message' => 'El usuario ingresao ya existe');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'type' => 'intentos_multiples', 'message' => 'Intente más tarde por favor');
    }
    catch (\Delight\Auth\DatabaseException $e) {
        return array('error' => TRUE, 'type' => 'db', 'message' => 'Error al insertar en base de datos');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'e' => $e);
    }
}

//Confirmar Email
function confirm($token){
    global $auth;
    try {
        $parts = json_decode(base64_decode(urldecode($token)));
        $auth->confirmEmail($parts->selector, $parts->token);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        return array('error' => TRUE, 'type' => 'token_invalido', 'message' => 'El token es inválido');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        return array('error' => TRUE, 'type' => 'token_expirado', 'message' => 'El token ha expirado');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'type' => 'intentos_multiples', 'messages' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'e' => $e);
    }
}

//Contraseña Perdida
function recover($correo){
    global $auth, $token;
    try {
        $token = '';
        $auth->forgotPassword($correo, function ($selector, $token) {
            $GLOBALS['token'] = urlencode(base64_encode(json_encode(compact('selector', 'token'))));
        });
        return array('error' => FALSE, 'token' => $token);
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'type' => 'correo', 'message' => 'El correo es inválido');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'type' => 'intentos_multiples', 'message' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'e' => $e);
    }
}

//Resetear Contraseña
function reset_password($token, $contraseña){
    global $auth;
    try {
        $parts = json_decode(base64_decode(urldecode($token)));
        $auth->resetPassword($parts->selector, $parts->token, $contraseña);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        return array('error' => TRUE, 'type' => 'token_invalido', 'message' => 'El token es inválido');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        return array('error' => TRUE, 'type' => 'token_expirado', 'message' => 'El token ha expirado');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'type' => 'contraseña', 'message' => 'Contraseña inválida');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'type' => 'intentos_multiples', 'message' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'e' => $e);
    }
}

//Cambiar contraseña del usuario actual (valida anterior)
function  change_current_password($antigua, $nueva){
    global $auth;
    try {
        $auth->changePassword($antigua, $nueva);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
        return array('error' => TRUE, 'type' => 'sin_ingresar', 'message' => 'No has iniciado sesión');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'type' => 'contraseña', 'message' => 'Contraseña incorrecta');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'e' => $e);
    }
}

//Cambiar contraseña de un usuario, sin necesidad de la anterior
function  change_password($password, $usuario = FALSE){
    global $tishdb;
    $id = ($usuario) ? $usuario :  id();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $res = $tishdb->update('users', compact('password'), compact('id'));
    if($res !== FALSE){
        return array('error' => FALSE, 'message' => 'Contraseña actualizada correctamente');
    } else {
        return array('error' => TRUE, 'message' => 'Ocurrió un error al cambiar la contraseña');
    }
}

//Cambiar role de usuario
function  change_role($id, $role){
    global $tishdb;
    $tishdb->update('users', compact('role'), compact('id'));
    return array('error' => FALSE, 'message' => 'El rol ha sido actualizado correctamente');
}

//Ingresar
function  login($correo, $pass, $recordar = FALSE){
    global $auth, $tishdb;
	$recordar = ($recordar) ? ((int) (60 * 60 * 24 * 365.25)) : null;
    try {
    	if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
			$auth->login($correo, $pass, $recordar);
		} else {
			$auth->loginWithUsername($correo, $pass, $recordar);
		}
        return array('error' => FALSE, 'id'=>$auth->getUserId());
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'type' => 'correo', 'message' => 'El correo es incorrecto');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'type' => 'contraseña', 'message' => 'La contraseña es incorrecta');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
        return array('error' => TRUE, 'type' => 'correo_no_verificado', 'message' => 'El usuario aún no ha sido verificado, revise su bandeja de entrada por favor');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'type' => 'intentos_multiples', 'message' => 'Intenta más tarde por favor');
    }
	catch(\Delight\Auth\UnknownUsernameException $e) {
		return array('error' => TRUE, 'type' => 'usuario_inexistente', 'message' => 'Usuario y/o contraseña erroneos');
	}
    catch (Exception $e) {
        return array('error' => TRUE, 'type' => 'desconocido', 'message' => 'Error desconocido', 'nombre' => get_class($e));
    }
}

//Cerrar Sesión
function  logout(){
    global $auth;
    $auth->logout();
}

//Obtener id del usuario actual
function  id(){
    global $auth;
    if ($auth->isLoggedIn()) {
        return $auth->getUserId();
    }
    else {
        return 0;
    }
}
//Alias
function get_current_user_id(){ return  id(); }

//Obtener el row del usuario actual
function  current_user(){
    global $auth,$tishdb;
    $id =  id();
    return $tishdb->get_row("SELECT * FROM users WHERE id={$id}");
}


//Obtener el rol del usuario actual
function get_current_user_role(){
    global $tishdb;
    return $tishdb->get_var("SELECT role FROM users WHERE id=". id());
}

//Comparar si la contraseña es correcta
function  check_password($password, $id_user = 0){
    global $tishdb;
    $id_user = ($id_user) ? $id_user :  id();
    if($id_user){
        $passwordInDatabase = $tishdb->get_var("SELECT password FROM users WHERE id = {$id_user}");
        return password_verify($password, $passwordInDatabase);
    } else {
        return false;
    }
}
