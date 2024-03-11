<form method="POST" id="formCrt">
    @csrf
    <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="ModalCreateLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalCreateLabel">Cadastro de Identificadores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="identif" id="identif" required>
                                    <option value="" selected="true"></option>
                                    <option value="Boleto">Boleto</option>
                                    <option value="Emprestimo">Empréstimo</option>
                                    <option value="Financiamento">Financiamento</option>
                                    <option value="Cartao">Cartão</option>
                                    <option value="Mensalidade">Mensalidade</option>
                                    <option value="Outro">Outro</option>
                                </select>
                                <label for="identif">Identificador</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <textarea name="descricao" id="descricao" class="form-control" style="height:5rem;" required></textarea>
                                <label for="descricao">Descrição</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="ativo" id="ativo" required>
                                    <option selected="true" value="Sim">Sim</option>
                                    <option value="Nao">Não</option>
                                </select>
                                <label for="ativo">Ativo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <input data-jscolor="{previewSize:'100%'}" class="form-control" id="id_hex"
                                    name="id_hex" value="#fff">
                                <label for="id_hex">Cor do tipo</label>
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
