<form method="POST" id="formUser">
    @method('PUT')
    @csrf
    <div class="col-md-12 mt-4">
        <div class="form-floating">
            <input type="text" class="form-control" id="name" name="name" value="{{ $userInfo->name }}">
            <label for="name">Nome</label>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" value="{{ $userInfo->email }}">
            <label for="email">E-mail registrado</label>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="input-group">
            <div class="form-floating">
                <input type="email" class="form-control" id="emailSec" name="emailSec"
                    value="{{ $userInfo->EMAIL_SECUNDARIO }}">
                <label for="emailSec">E-mail Secundário</label>
            </div>
            <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Utilize para receber suas notificações em um segundo e-mail"><i
                    class="fa-solid fa-circle-info"></i></span>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </div>
</form>
