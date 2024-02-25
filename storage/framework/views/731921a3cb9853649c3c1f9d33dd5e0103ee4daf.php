<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/nice-select.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/dropzone.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/media-uploader.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Video News Area')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <?php echo $__env->make('backend/partials/message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->make('backend/partials/error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-lg-12 mt-t">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"><?php echo e(__('Video News Area Settings')); ?></h4>

                        <form action="<?php echo e(route('admin.home20.video')); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <?php $__currentLoopData = $all_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a class="nav-item nav-link <?php if($key == 0): ?> active <?php endif; ?>" id="nav-home-tab" data-slug="<?php echo e($lang->slug); ?>" data-toggle="tab" href="#nav-home-<?php echo e($lang->slug); ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo e($lang->name); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                <?php $__currentLoopData = $all_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php if($key == 0): ?> show active <?php endif; ?>" data-slug="<?php echo e($lang->slug); ?>" id="nav-home-<?php echo e($lang->slug); ?>" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="home20_video_news_section_<?php echo e($lang); ?>_section_title"><?php echo e(__('Section Title')); ?></label>
                                            <input type="text" name="home20_video_news_section_<?php echo e($lang->slug); ?>_section_title" value="<?php echo e(get_static_option('home20_video_news_section_'.$lang->slug.'_section_title')); ?>" class="form-control" >
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="form-group">
                                <label for="home20_video_news_section_items"><?php echo e(__('News Items')); ?></label>
                                <input type="number" name="home20_video_news_section_items" value="<?php echo e(get_static_option('home20_video_news_section_items')); ?>" class="form-control" >
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4"><?php echo e(__('Update Settings')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/backend/js/jquery.nice-select.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {
            let niceSelect = $('.nice-select');
            if(niceSelect.length > 0){
                niceSelect.niceSelect();
            }
            fetchCategories($('.nav-tabs .nav-item.active').data('slug'));
            $(document).on('click','.nav-tabs .nav-item',function (e){
                //categories_select
                let langSlug = $(this).data('slug');
                fetchCategories(langSlug)
            });

            function fetchCategories(lang){
                $.ajax({
                   url: "<?php echo e(route('admin.blog.category.by.lang')); ?>",
                   type: 'POST',
                   data : {
                       _token : "<?php echo e(csrf_token()); ?>",
                       lang:lang
                   },
                    success: function (data){
                       //append data
                        let tabContainer = $('.tab-content .tab-pane[data-slug="'+lang+'"]');
                        let markup = '';
                        //loop through categories
                        $.each(data.categories,function (index,value){
                            let selected = data.selected.includes(value.id.toString()) ? 'selected' : '';
                            markup += '<option '+selected+' value="'+value.id+'">'+value.name+'</option>';
                        });
                        tabContainer.find('.categories_select').append(markup);
                        niceSelect.niceSelect('update');
                    }
                });
            }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Volumes/DATA/Projects/Laravel/Work/shaki/resources/views/backend/pages/home/newspaper/video-area.blade.php ENDPATH**/ ?>