import Switchery from 'switchery';


const apply = (selector) => {
    $.each($(selector), function (key, value) {
        let $size = "", $color = "", $sizeClass = "", $colorCode = "";
        $size = $(this).data('size');
        let $sizes = {
            'lg': "large",
            'sm': "small",
            'xs': "xsmall"
        };
        if ($(this).data('size') !== undefined) {
            $sizeClass = "switchery switchery-" + $sizes[$size];
        } else {
            $sizeClass = "switchery";
        }

        $color = $(this).data('color');
        let $colors = {
            'primary': "#967ADC",
            'success': "#37BC9B",
            'danger': "#DA4453",
            'warning': "#F6BB42",
            'info': "#3BAFDA"
        };
        if ($color !== undefined) {
            $colorCode = $colors[$color];
        } else {
            $colorCode = "#37BC9B";
        }
        new Switchery($(this)[0], {className: $sizeClass, color: $colorCode});
    });
};

export default {
    apply
}