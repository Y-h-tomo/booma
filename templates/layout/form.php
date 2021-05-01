<?php
$options = array(
  'action' => 'done',
  'type' => 'file',
  'inputDefaults' => array('legend' => false, 'label' => false, 'div' => false),
  'class' => 'form-horizontal'
);
?>
<?php echo $this->Form->create(false, $options); ?>
<!-- 画像ファイル指定 -->
<div class="control-group">
  <label class="control-label" for="fileInput">画像</label>
  <div class="controls">
    <?php echo $this->Form->file('Hoge.image', array('id' => 'fileInput', 'class' => 'input-file uniform_on')); ?>
  </div>
</div>
<!-- サムネイル画像表示 -->
<div class="control-group">
  <label class="control-label" for="fileInput">サムネイル画像</label>
  <div class="controls">
    <p>指定なし</p>
  </div>
</div>
<?php echo $this->Form->end(); ?>