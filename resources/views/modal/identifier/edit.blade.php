<form method="POST" id="formEdt">
    @method('PUT')
    @csrf

    <div class="modal fade" id="ModalEdit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalEditLabel">Edição de Identificadores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idIdentif">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="identifEdt" id="identifEdt" required>
                                    <option value="Boleto" data-tipo="Boleto">Boleto</option>
                                    <option value="Emprestimo" data-tipo="Emprestimo">Empréstimo</option>
                                    <option value="Financiamento" data-tipo="Financiamento">Financiamento</option>
                                    <option value="Cartao" data-tipo="Cartao">Cartão</option>
                                    <option value="Mensalidade" data-tipo="Mensalidade">Mensalidade</option>
                                    <option value="Outro" data-tipo="Outro">Outro</option>
                                </select>
                                <label for="identifEdt">Identificador</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <textarea name="descricaoEdt" id="descricaoEdt" class="form-control" style="height:5rem;"></textarea>
                                <label for="descricaoEdt">Descrição</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="ativoEdt" id="ativoEdt" required>
                                    <option value="Sim" data-ativo="Sim">Sim</option>
                                    <option value="Nao" data-ativo="Nao">Não</option>
                                </select>
                                <label for="ativoEdt">Ativo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <input data-jscolor="{previewSize:'100%'}" class="form-control" id="id_hexEdt"
                                    name="id_hexEdt">
                                <label for="id_hexEdt">Cor do tipo</label>
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
