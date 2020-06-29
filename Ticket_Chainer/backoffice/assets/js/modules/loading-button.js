const loadingButton = ($button, loadingText = 'Chargement…') => {
    if (!isLoadingButton($button)) {
        const originalText = $button.html();
        $button.attr('data-original-text', originalText);
        $button.attr('data-loading-text', `<i class="fa fa-spinner spinner"></i> ${loadingText}`);
    }
};

const isLoadingButton = ($button) => {
    if (!$button.length) {
        throw Error(`${$button} element not found`)
    }
    return $button.data('originalText') || $button.data('loadingText');
};

export const startLoading = ($button) => {
    loadingButton($button);
    $button.html($button.data('loadingText'))
};

export const stopLoading = ($button) => {
    loadingButton($button);
    $button.html($button.data('originalText'))
};
