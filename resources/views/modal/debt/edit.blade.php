<form method="POST" id="formEdt">
    @method('PUT')
    @csrf
    <div class="modal fade" id="ModalEdit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalEditLabel">Edição de Devedores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <input type="text" name="nomeEdt" id="nomeEdt" class="form-control" required>
                                <label for="nomeEdt">Nome</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <input type="email" name="emailEdt" id="emailEdt" class="form-control">
                                <label for="emailEdt">E-mail (opcional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="ativoEdt" id="ativoEdt" required>
                                    <option value="Sim" data-ativo="Sim">Sim</option>
                                    <option value="Nao" data-ativo="Nao">Não</option>
                                </select>
                                <label for="ativoEdt">Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</form>
