<div class="row">

    <div class="form-group col-md-6">
        <label class="form-control-label">Razão Social</label>
        <input type="text" name="razao" placeholder="Razão Social" class="form-control" value="<?php echo esc($fornecedor->razao); ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-control-label">Nome Fantasia</label>
        <input type="text" name="fantasia" placeholder="Nome Fantasia" class="form-control" value="<?php echo esc($fornecedor->fantasia); ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-control-label">CNPJ</label>
        <input type="text" name="cnpj" placeholder="CNPJ" class="form-control cnpj" value="<?php echo esc($fornecedor->cnpj); ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-control-label">IE</label>
        <input type="text" name="ie" placeholder="IE" class="form-control" value="<?php echo esc($fornecedor->ie); ?>">
    </div>

    <div class="form-group col-md-3">
        <label class="form-control-label">CEP</label>
        <input type="text" name="cep" placeholder="CEP" class="form-control cep" value="<?php echo esc($fornecedor->cep); ?>">
        <div id="cep"></div>
    </div>

    <div class="form-group col-md-7">
        <label class="form-control-label">Endereço</label>
        <input type="text" name="endereco" placeholder="Endereço" class="form-control" value="<?php echo esc($fornecedor->endereco); ?>" readonly>
    </div>

    <div class="form-group col-md-2">
        <label class="form-control-label">Número</label>
        <input type="text" name="numero" placeholder="Número" class="form-control" value="<?php echo esc($fornecedor->numero); ?>">
    </div>

    <div class="form-group col-md-5">
        <label class="form-control-label">Cidade</label>
        <input type="text" name="cidade" placeholder="Cidade" class="form-control" value="<?php echo esc($fornecedor->cidade); ?>" readonly>
    </div>

    <div class="form-group col-md-5">
        <label class="form-control-label">Bairro</label>
        <input type="text" name="bairro" placeholder="Bairro" class="form-control" value="<?php echo esc($fornecedor->bairro); ?>" readonly>
    </div>

    <div class="form-group col-md-2">
        <label class="form-control-label">UF</label>
        <input type="text" name="uf" placeholder="UF" class="form-control" value="<?php echo esc($fornecedor->uf); ?>" readonly>
    </div>

    <div class="form-group col-md-6">
        <label class="form-control-label">Telefone</label>
        <input type="text" name="telefone" placeholder="Telefone" class="form-control sp_celphones" value="<?php echo esc($fornecedor->telefone); ?>">
    </div>

    <div class="form-group col-md-6">
        <label class="form-control-label">Celular</label>
        <input type="text" name="celular" placeholder="Celular" class="form-control sp_celphones" value="<?php echo esc($fornecedor->celular); ?>">
    </div>

    <div class="form-group col-md-12">
        <label class="form-control-label">E-mail</label>
        <input type="email" name="email" placeholder="E-Mail" class="form-control" value="<?php echo esc($fornecedor->email); ?>">
    </div>

</div>

    <div class="custom-control custom-checkbox">
        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" name="ativo" value="1" class="custom-control-input" id="ativo" <?php if($fornecedor->ativo == true): ?> checked <?php endif; ?> >
        <label class="custom-control-label" for="ativo">Fornecedor Ativo</label>
    </div>