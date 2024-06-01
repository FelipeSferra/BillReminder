<form method="POST" id="formCrt">
    @csrf
    <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="ModalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalCreateLabel">Cadastro de Devedores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <input type="text" name="nome" id="nome" class="form-control" required>
                                <label for="nome">Nome</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <input type="email" name="email" id="email" class="form-control">
                                <label for="email">E-mail (opcional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="ativo" id="ativo" required>
                                    <option selected="true" value="Sim">Sim</option>
                                    <option value="Nao">Não</option>
                                </select>
                                <label for="ativo">Ativo</label>
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
