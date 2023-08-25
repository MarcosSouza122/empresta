<div class="form-group">
    <label for="name"><b>Nome</b></label>
    <input type="text" class="form-control" name="name" placeholder="Nome Completo" value="{{ old('name', $user->name) }}">   
</div>
<div class="form-group">
    <label for="username"><b>Login</b></label>
    <input type="text" class="form-control" name="username" placeholder="Login" value="{{ old('username', $user->username) }}">   
</div>
<div class="form-group">
    <label for="password"><b>Senha</b></label>
    <input class="form-control" name="password" type="password" placeholder="Senha" value="{{ old('password', $user->password) }}">   
</div>
<div class="form-group">
    <a href="users" class="btn btn-primary float-right">Voltar</a>
    <button type="submit" class="btn btn-success float-left">Enviar</button> 
</div>
