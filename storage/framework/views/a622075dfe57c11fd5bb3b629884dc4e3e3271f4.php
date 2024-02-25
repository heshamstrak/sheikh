<?php if(session()->has('msg')): ?>
    <div class="alert alert-<?php echo e(session('type')); ?>">
        <?php echo session('msg'); ?>

    </div>
<?php endif; ?>
<?php /**PATH /Volumes/DATA/Projects/Laravel/Work/shaki/resources/views/backend/partials/message.blade.php ENDPATH**/ ?>