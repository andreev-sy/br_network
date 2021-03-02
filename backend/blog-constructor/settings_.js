const settings = {
    DRAFT_ID: $('[data-ctor-draft-id]').data('ctor-draft-id'),
    PREVIEW_LINK: $('[data-ctor-preview-link]').data('ctor-preview-link'),
    SAVE_LINK: $('[data-ctor-save-link]').data('ctor-save-link'),

    DRAFT_REL_FIELD: 'blog_post_id',
    BLOCK_REL_FIELD: 'blog_block_id',
    BLOCK_REL_NAME: 'blogBlock',

    GET_BLOCKS: '/blog-blocks/?fields=id,name,type&expand=blockTypeLabel',
    API_DRAFT_BLOCKS: '/blog-post-blocks/',
    get GET_DRAFT_BLOCKS () {return `${this.API_DRAFT_BLOCKS}?expand=${this.BLOCK_REL_NAME},mediaTargets`},
    POST_BLOCKS_SORTINGS: '/blog-post-blocks/sort/',

    POST_FILE: '/media/upload/',
    POST_FILE_FASTVIEW: '/media/fast-view/',
    LIST_STYLES: [],
    BLOCK_SHARED_SETTINGS: [
        {
            title: 'Ширина блока',
            slug: 'size',
            type: 'select',
            variants: [
                { value: 's', label: 'Маленький', default: true },
                { value: 'm', label: 'Обычный' },
                { value: 'l', label: 'Большой' },
            ],
        },
        {
            title: 'Цветовая тема',
            slug: 'color',
            type: 'select',
            variants: [
                { value: 'default', label: 'Стандартный', default: true },
                { value: 'blue', label: 'Синий' },
                { value: 'yellow', label: 'Желтый' },
                { value: 'gray', label: 'Серый' },
            ],
        },
        {
            title: 'Отступы',
            slug: 'margin',
            type: 'select',
            variants: [
                { value: 'default', label: 'Обычный', default: true },
                { value: 'large', label: 'Большой' },
                { value: 'nomargin', label: 'Без отступов' },
            ],
        },
    ],
};

export default settings;