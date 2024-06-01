<form method="POST" id="formNotif" name="formNotif">
    @method('PUT')
    @csrf
    <div class="col-md-12 mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <label>
                Ativar e-mail de gastos?
            </label>
            <input type="checkbox" id="notifGasto" name="notifGasto" value="S">
        </div>
    </div>
    <div class="col-md-12 mt-3" id="divGastos" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-6">
                <label for="emailGastos">De que maneira deseja receber o e-mail de gastos?</label>
            </div>
            <div class="col-md-6">
                <select class="form-control" name="emailGastos" id="emailGastos">
                    <option selected="true" value="Mensal" data-tipo="Mensal">Mensal</option>
                    <option value="Quinzenal" data-tipo="Quinzenal">Quinzenal</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <label>
                Ativar e-mail de vencimentos?
            </label>
            <input type="checkbox" id="notifVenc" name="notifVenc" value="S">
        </div>
    </div>
    <div class="col-md-12 mt-3" id="divVenc" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-6">
                <label for="emailVenc">Quantos dias antes do vencimento deseja receber o e-mail?</label>
            </div>
            <div class="col-md-6">
                <input type="number" class="form-control" id="emailVenc" name="emailVenc" value="5" step="1" min="5"
                    max="45" required>
            </div>

        </div>
    </div>
    <div class="col-md-12 mt-3 text-end">
        <button type="submit" class="btn btn-primary" id="saveNotify" disabled>Salvar</button>
    </div>
</form>
