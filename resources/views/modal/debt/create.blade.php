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
                                <select class="form-control" name="tipo_conta" id="tipo_conta" required>
                                    <option value="" selected="true"></option>
                                    {{--  @foreach ($identifiers as $identifier)
                                        @if ($identifier->ATIVO === 'Sim')
                                            <option value="{{ $identifier->id }}">{{ $identifier->DESCRICAO }}</option>
                                        @endif
                                    @endforeach --}}
                                </select>
                                <label for="tipo_conta">Tipo de conta</label>
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
                                <input type="text" name="nome" id="nome" class="form-control" required>
                                <label for="nome">Nome</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <input type="email" name="email" id="email" class="form-control">
                                <label for="email">E-mail (opcional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="input-group mt-2">
                                <span class="input-group-text">R$</span>
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="valor" name="valor"
                                        step="0.01" min="1" required>
                                    <label for="valor">Valor</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mt-2">
                                <input type="number" class="form-control" id="parcelas" name="parcelas" min="1"
                                    required>
                                <label for="parcelas">Parcelas</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mt-2">
                                <input type="date" class="form-control" id="vencimento" name="vencimento" required>
                                <label for="vencimento">Vencimento</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mt-2">
                                <select class="form-control" name="recriar" id="recriar" required>
                                    <option selected="true" value="Nao">Não</option>
                                    <option value="Sim">Sim</option>
                                </select>
                                <label for="recriar">Recriar</label>
                            </div>
                        </div>

                        <input type="hidden"name="status" id="status" value="Pagar">

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
