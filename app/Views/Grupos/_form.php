<div class="form-group">
    <label class="form-control-label">Nome</label>
    <input type="text" name="nome" placeholder="Nome" class="form-control" value="<?php echo esc($grupo->nome); ?>">
</div>

<div class="form-group">
    <label class="form-control-label">Descrição</label>
    <input type="text" name="descricao" placeholder="Descrição" class="form-control" value="<?php echo esc($grupo->descricao); ?>">
</div>



<div class="custom-control custom-checkbox">
    <input type="hidden" name="exibir" value="0">
    <input type="checkbox" name="exibir" value="1" class="custom-control-input" id="exibir" <?php if($grupo->exibir == true): ?> checked <?php endif; ?> >
    <label class="custom-control-label" for="exibir">Exibir Grupo</label>
    <a tabindex="0"style="text-decoration: none;" role="button" data-toggle="popover" data-trigger="focus" title="<b class='text-danger'>Importante</b>" data-content="Se Esse Grupo for Definido como <b>Exibir Grupo de Acesso</b> ele será habilitado para definição do Responsalvel Tecnico">&nbsp;<i class="fa fa-question-circle fa-lg text-danger"></i></a>
</div>