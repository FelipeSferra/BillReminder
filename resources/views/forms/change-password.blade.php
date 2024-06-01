<form method="POST" id="formPass" name="formPass">
    @csrf

    <div class="d-flex justify-content-center">
        <div class="col-md-6 mt-3">
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password">
                <label for="password">Senha atual</label>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="col-md-6 mt-3">
            <div class="form-floating">
                <input type="password" class="form-control" id="new_password" name="new_password">
                <label for="new_password">Nova senha</label>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <div class="col-md-6 mt-3">
            <div class="form-floating">
                <input type="password" class="form-control" id="new_password_confirmation"
                    name="new_password_confirmation">
                <label for="new_password_confirmation">Confirme a nova senha</label>
            </div>
        </div>
    </div>
    <div class="row gutters">
        <div class="col-md-12 mt-3">
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>

</form>
