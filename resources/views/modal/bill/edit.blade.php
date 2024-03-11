<form method="POST" id="formEdt">
    @method('PUT')
    @csrf
    <div class="modal fade" id="ModalEdit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalEditLabel">Edição de Contas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="tipo_contaEdt" id="tipo_contaEdt" required>
                                    @foreach ($identifiers as $identifier)
                                        @if ($identifier->ATIVO === 'Sim')
                                            <option value="{{ $identifier->id }}" data-tipo = "{{ $identifier->id }}">
                                                {{ $identifier->DESCRICAO }}</option>
                                        @else
                                            <option value="" selected></option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="tipo_contaEdt">Tipo de conta</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating mt-2">
                                <textarea name="descricaoEdt" id="descricaoEdt" class="form-control" style="height:5rem;" required></textarea>
                                <label for="descricaoEdt">Descrição</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="input-group mt-2">
                                <span class="input-group-text">R$</span>
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="valorEdt" name="valorEdt"
                                        step="0.01" min="1" required>
                                    <label for="valorEdt">Valor</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mt-2">
                                <input type="number" class="form-control" id="parcelasEdt" name="parcelasEdt"
                                    min="1" required>
                                <label for="parcelasEdt">Parcelas</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mt-2">
                                <input type="date" class="form-control" id="vencimentoEdt" name="vencimentoEdt"
                                    required>
                                <label for="vencimentoEdt">Vencimento</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="recriarEdt" id="recriarEdt" required>
                                    <option selected="true" value="Sim" data-rec="Sim">Sim</option>
                                    <option value="Nao" data-rec="Nao">Não</option>
                                </select>
                                <label for="recriarEdt">Recriar</label>
                            </div>
                        </div>
                        <input type="hidden"name="statusEdt" id="statusEdt">
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
