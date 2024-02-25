<?php


namespace App\PageBuilder\Addons\Team;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\ColorPicker;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Notice;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;
use App\TeamMember;
use App\Testimonial;

class TeamSliderStyleFour extends PageBuilderBase
{

    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'team/slider-04.png';
    }

    /**
     * @inheritDoc
     */
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = LanguageHelper::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'section_title_'.$lang->slug,
                'label' => __('Section Title'),
                'value' => $widget_saved_values['section_title_' . $lang->slug] ?? null,
            ]);
            $output .= Textarea::get([
                'name' => 'section_description_'.$lang->slug,
                'label' => __('Section Description'),
                'value' => $widget_saved_values['section_description_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Select::get([
            'name' => 'section_title_alignment',
            'label' => __('Section Title Alignment'),
            'options' => [
                'left-align' => __('Left Align'),
                'center-align' => __('Center Align'),
                'right-align' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_alignment'] ?? null,
            'info' => __('set alignment of section title')
        ]);
        $output .= Select::get([
            'name' => 'section_title_content_alignment',
            'label' => __('Section Title Content Alignment'),
            'options' => [
                'justify-content-start' => __('Left Align'),
                'justify-content-center' => __('Center Align'),
                'justify-content-end' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_content_alignment'] ?? null,
            'info' => __('set alignment of section title content alignment')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);
        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Number::get([
            'name' => 'slider_items',
            'label' => __('Slider Item'),
            'value' => $widget_saved_values['slider_items'] ?? 4,
            'info' => __('enter how many item you want to show in a row of slider'),
        ]);
        $output .= Notice::get([
           'type' => 'secondary',
           'text' => __('Section Settings')
        ]);
        $output .= ColorPicker::get([
            'name' => 'background_color',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['background_color'] ?? '',
        ]);
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 110,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 110,
            'max' => 200,
        ]);

        // add padding option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function frontend_render()
    {
        $settings = $this->get_settings();
        $current_lang = LanguageHelper::user_lang_slug();
        $section_title = SanitizeInput::esc_html($settings['section_title_'.$current_lang]);
        $section_description = SanitizeInput::esc_html($settings['section_description_'.$current_lang]);
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);
        $section_title_content_alignment = SanitizeInput::esc_html($settings['section_title_content_alignment']);
        $order_by = SanitizeInput::esc_html($settings['order_by']);
        $order = SanitizeInput::esc_html($settings['order']);
        $items = SanitizeInput::esc_html($settings['items']);
        $slider_items = SanitizeInput::esc_html($settings['slider_items']);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $background_color = SanitizeInput::esc_html($settings['background_color']);


        $team_member = TeamMember::query()->where(['lang' => $current_lang])->orderBy($order_by,$order)->get();

        if(!empty($items)){
            $team_member = $team_member->take($items);
        }
        $category_markup = '';
        foreach ($team_member as $item){
            $image = render_image_markup_by_attachment_id($item->image);
            $name = $item->name;
            $designation = $item->designation;

            $social_links_markup = '<ul class="social-icons">';
            $social_fields = array(
                'icon_one' => 'icon_one_url',
                'icon_two' => 'icon_two_url',
                'icon_three' => 'icon_three_url',
            );
            foreach($social_fields as $key => $value){
                $social_links_markup .= '<li><a href="'.$item->$value.'"><i class="'.$item->$key.'"></i></a></li>';
            }
            $social_links_markup .= '</ul>';

            $category_markup .= <<<HTML
<div class="team-section team-member-style-01">
    <div class="team-img-cont">
         {$image}
        <div class="social-link">
            {$social_links_markup}
        </div>
    </div>
    <div class="team-text">
        <h4 class="title">{$name}</h4>
        <span>{$designation}</span>
    </div>
</div>
HTML;
        }

        $section_title_markup = '';
        if (!empty($section_title)){
            $section_title_markup .= <<<HTML
<div class="row {$section_title_content_alignment}">
    <div class="col-lg-8">
        <div class="section-title margin-bottom-60 industry-home {$section_title_alignment}">
            <h2 class="title">{$section_title}</h2>
            <p>{$section_description}</p>
        </div>
    </div>
</div>
HTML;
        }

        return <<<HTML
<div class="creative-team-two" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}" style="background-color: {$background_color}">
    <div class="container">
        {$section_title_markup}
        <div class="row">
            <div class="col-lg-12">
                <div class="team-memeber-carousel-area margin-top-10">
                    <div class="global-carousel-init logistic-dots"
                         data-loop="true"
                         data-desktopitem="{$slider_items}"
                         data-mobileitem="1"
                         data-tabletitem="2"
                         data-dots="true"
                         data-autoplay="true"
                         data-margin="30"
                    >
                    {$category_markup}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Team Slider: 04');
    }
}