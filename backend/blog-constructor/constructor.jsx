import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { SortableContainer, SortableElement } from 'react-sortable-hoc';
import { Editor as TinyMceEditor } from '@tinymce/tinymce-react';
import arrayMove from 'array-move';
import Select from 'react-select';
import _ from 'lodash';
import CyrillicToTranslit from 'cyrillic-to-translit-js';
import { Line } from 'rc-progress';
import Checkbox from 'rc-checkbox';
import Sticky from 'react-stickynode';

const AVAILABLE_BLOCKS =
    'blog-blocks/?fields=id,name,type&expand=blockTypeLabel';
const PAGE_BLOCKS = 'blog-post-blocks/?expand=blogBlock,mediaTargets&sort=sort';
const PUT_PAGE_BLOCKS = 'blog-post-blocks';
const POST_BLOCKS_SORTINGS = 'blog-post-blocks/sort/';
const PAGE_ID_REL = 'blog_post_id';
const BLOCK_ID_REL = 'blog_block_id';
const PLAIN_TEXT_INPUT = 'text';
const IMAGE_INPUT = 'image'; //TODO expand
const RICH_TEXT_INPUT = 'html';

const SETTINGS_SIZES = [
    { value: 's', label: 'Маленький' },
    { value: 'm', label: 'Обычный' },
    { value: 'l', label: 'Большой' },
];
const SETTINGS_COLORS = [
    { value: 'default', label: 'Стандартный' },
    { value: 'blue', label: 'Синий' },
    { value: 'yellow', label: 'Желтый' },
    { value: 'gray', label: 'Серый' },
];

const SETTINGS_MARGIN = [
    { value: 'default', label: 'Обычный' },
    { value: 'large', label: 'Большой' },
    { value: 'nomargin', label: 'Без отступов' },
];

const INITIAL_SETTINGS = {
    setting_size: 's',
    setting_color: 'default',
    setting_margin: 'default',
    paragraph: {
        isset: false,
        asHeading: false,
        heading: '',
        alias: '',
        headingVar: '',
        level: 1,
    },
};

const PlainTextInput = ({ initialValue, onBlur, onInput, onInputReady }) => {
    const [loaded, setLoaded] = useState(false);
    useEffect(() => {
        if (loaded) {
            onInputReady(true);
        }
    }, [loaded]);
    return (
        <div>
            <TinyMceEditor
                onBlur={onBlur}
                initialValue={initialValue}
                init={{
                    height: 110,
                    menubar: false,
                    plugins: ['paste', 'autoresize', 'emoticons', 'charmap'],
                    autoresize_bottom_margin: 10,
                    toolbar:
                        'undo redo | bold italic forecolor backcolor | emoticons charmap',
                    nowrap: true,
                    forced_root_block: '',
                    force_br_newlines: true,
                    force_p_newlines: false,
                    className: 'form-control',
                    language: 'ru',
                }}
                inline={true}
                onEditorChange={onInput}
                onInit={() => onInputReady(true)}
            />
        </div>
    );
};

const RichTextInput = ({
    initialValue,
    onBlur,
    onInput,
    onInputReady,
    mediaTargetId,
}) => {
    const [loaded, setLoaded] = useState(false);
    const initEditor = (editor) => {
        editor.hide();
        $(editor.getElement()).on('focus', () => {
            editor.show();
            editor.focus();
        });
        editor.on('blur', () => editor.hide());
        setLoaded(true);
    };
    useEffect(() => {
        if (loaded) {
            onInputReady(true);
        }
    }, [loaded]);
    return (
        <div>
            <TinyMceEditor
                onBlur={onBlur}
                initialValue={initialValue}
                init={{
                    menubar: true,
                    autoresize_bottom_margin: 10,
                    language: 'ru',
                    plugins: [
                        'advlist autolink lists link charmap preview anchor emoticons nonbreaking',
                        'searchreplace visualblocks code fullscreen autoresize',
                        'insertdatetime media table contextmenu paste image',
                    ],
                    image_caption: true,
                    toolbar:
                        'undo redo | bold italic forecolor backcolor|' +
                        ' aligncenter alignright alignjustify |' +
                        'bullist numlist outdent indent | image link |' +
                        ' emoticons charmap nonbreaking',
                    nowrap: true,
                    className: 'form-control',
                    images_upload_handler: function (
                        blobInfo,
                        success,
                        failure,
                        progress
                    ) {
                        const onSuccess = (response) => {
                            if (
                                Array.isArray(response.initialPreview) &&
                                response.initialPreview.length > 0
                            ) {
                                success(response.initialPreview[0]);
                            } else failure(JSON.stringify(response));
                        };
                        const onError = (response) => {
                            failure(JSON.stringify(response));
                        };

                        const formData = new FormData();
                        formData.append(
                            'file',
                            blobInfo.blob(),
                            blobInfo.filename()
                        );
                        formData.append('media_target_id', mediaTargetId);

                        $.ajax({
                            xhr: function () {
                                var xhr = new XMLHttpRequest();
                                xhr.upload.addEventListener(
                                    'progress',
                                    function (e) {
                                        if (e.lengthComputable) {
                                            progress(
                                                (e.loaded / e.total) * 100
                                            );
                                        }
                                    },
                                    false
                                );
                                return xhr;
                            },
                            method: 'post',
                            url: '/media/upload/',
                            data: formData,
                            success: onSuccess,
                            error: onError,
                            processData: false,
                            contentType: false,
                        });
                    },
                }}
                // inline={true}
                onEditorChange={onInput}
                onInit={(e, editor) => {
                    initEditor(editor);
                }}
            />
        </div>
    );
};

const FileInput = ({ mediaTargetId, onInputReady }) => {
    const inputref = React.createRef();
    const [loaded, setLoaded] = useState(false);

    useEffect(() => {
        const close = (elem) => (response) => {
            $(elem).mediaInputGen(response, () => setLoaded(true));
        };
        $.ajax({
            url: '/media/fast-view/',
            type: 'post',
            dataType: 'json',
            data: { media_target_id: mediaTargetId },
            success: close(inputref.current),
            error: function (response) {
                console.error(response);
            },
        });
    }, [mediaTargetId]);

    useEffect(() => {
        if (loaded) {
            onInputReady(true);
        }
    }, [loaded]);

    return (
        <input
            type='file'
            className='media_upload'
            name=''
            multiple
            data-media_target_id={mediaTargetId}
            ref={inputref}
        />
    );
};

const SortableItem = SortableElement(
    ({
        item: pageBlock,
        ajax,
        onDeleteBlock,
        onExpandedStateChange,
        onBlockLoaded,
    }) => {
        let inputsMeta = {},
            initialInputsContent = _.cloneDeep(INITIAL_SETTINGS),
            inputsReadyInitState = {},
            customSettings = [];
        try {
            inputsMeta = JSON.parse(pageBlock.blogBlock.inputs) || {};
            for (const inputType of Object.keys(inputsMeta)) {
                if (inputType == 'settings') {
                    for (const { title, slug, type, variants } of inputsMeta[
                        inputType
                    ]) {
                        const settingVarName = `setting_${slug}`;
                        const defaultValue = variants[0].value;
                        customSettings.push({
                            title,
                            type,
                            variants,
                            defaultValue,
                            settingVarName,
                        });
                    }
                } else {
                    for (const { heading = null, slug } of inputsMeta[
                        inputType
                    ]) {
                        const inputVarName = `${inputType}_${slug}`;
                        inputsReadyInitState = _.assign(
                            {},
                            inputsReadyInitState,
                            {
                                [inputVarName]: false,
                            }
                        );
                        if (heading) {
                            initialInputsContent = _.assign(
                                {},
                                initialInputsContent,
                                {
                                    paragraph: {
                                        ...initialInputsContent.paragraph,
                                        isset: true,
                                        asHeading: true,
                                        level: heading,
                                        headingVar: inputVarName,
                                    },
                                }
                            );
                            break;
                        }
                    }
                }
            }
            if (pageBlock.content) {
                const parsed = JSON.parse(pageBlock.content) || {};
                initialInputsContent = _.assign(
                    {},
                    initialInputsContent,
                    parsed
                );
            }
        } catch (error) {
            return <li>{error.message}</li>;
        }

        const [inputsContent, setInputContent] = useState(initialInputsContent);
        const [inputsReadyState, setInputsReadyState] = useState(
            inputsReadyInitState
        );

        const [isDirty, setIsDirty] = useState(false);
        const [mode, setMode] = useState('editor');

        const headingVar = inputsContent.paragraph.headingVar;

        const setParagraphAsHeading = (newinputsContent) => {
            const html = newinputsContent[headingVar];
            const stripped = new DOMParser().parseFromString(html, 'text/html')
                .documentElement.textContent;
            newinputsContent.paragraph.heading = stripped;
            newinputsContent.paragraph.alias = CyrillicToTranslit()
                .transform(stripped, '-')
                .replace(/[^a-zA-Z-]/g, '')
                .toLowerCase();
        };

        const onInput = ({ alias, data }) => {
            const newinputsContent = _.cloneDeep(inputsContent);
            newinputsContent[alias] = data;
            if (
                headingVar != null &&
                alias == headingVar &&
                inputsContent.paragraph.isset &&
                inputsContent.paragraph.asHeading
            ) {
                setParagraphAsHeading(newinputsContent);
            }
            setIsDirty(true);
            setInputContent(newinputsContent);
        };

        const onParagraphCheckbox = (e) => {
            const checked = e.target.checked;
            const newinputsContent = _.cloneDeep(inputsContent);
            newinputsContent.paragraph.isset = checked;
            if (checked && inputsContent.paragraph.asHeading) {
                setParagraphAsHeading(newinputsContent);
            }
            setInputContent(newinputsContent);
        };

        const onParagraphAsHeadingCheckbox = (e) => {
            const checked = e.target.checked;
            const newinputsContent = _.cloneDeep(inputsContent);
            newinputsContent.paragraph.asHeading = checked;
            if (checked) {
                setParagraphAsHeading(newinputsContent);
            }
            setInputContent(newinputsContent);
        };

        const onParagraphHeadingChange = (e) => {
            const value = e.target.value;
            const alias = CyrillicToTranslit()
                .transform(value, '-')
                .replace(/[^a-zA-Z-]/g, '')
                .toLowerCase();
            const newinputsContent = _.cloneDeep(inputsContent);
            newinputsContent.paragraph.heading = value;
            newinputsContent.paragraph.alias = alias;
            setInputContent(newinputsContent);
        };

        const onSettingChange = ({ settingVarName, value }) => {
            setInputContent(
                _.assign(_.cloneDeep(inputsContent), {
                    [settingVarName]: value,
                })
            );
        };

        const onSave = () => {
            ajax(
                'put',
                PUT_PAGE_BLOCKS + '/' + pageBlock.id + '/',
                (res) => {
                    setIsDirty(false);
                },
                { content: JSON.stringify(inputsContent) },
                (res) => {
                    setIsDirty(false);
                }
            );
        };

        const onInputReady = (varName) => (state) => {
            setInputsReadyState(
                _.assign(_.cloneDeep(inputsReadyState), {
                    [varName]: state,
                })
            );
        };

        useEffect(() => {
            if (!isDirty) {
                onSave();
            }
        }, [isDirty, inputsContent]);

        useEffect(() => {
            const isLoaded = !_.values(inputsReadyState).includes(false);
            if (isLoaded) {
                setIsDirty(false);
            }
            onBlockLoaded(isLoaded);
        }, [inputsReadyState]);

        const inputComponents = Object.keys(inputsMeta).reduce(
            (acc, inputType) => {
                let inputs = [];
                switch (inputType) {
                    case PLAIN_TEXT_INPUT:
                        inputs = inputsMeta[inputType].map(
                            ({ title, slug }, idx) => {
                                const contentVarName = `${inputType}_${slug}`;
                                return {
                                    title,
                                    inputElement: (
                                        <PlainTextInput
                                            initialValue={
                                                (inputsContent[
                                                    contentVarName
                                                ] &&
                                                    inputsContent[
                                                        contentVarName
                                                    ]) ||
                                                ''
                                            }
                                            onInput={(data) =>
                                                onInput({
                                                    alias: contentVarName,
                                                    data,
                                                })
                                            }
                                            onBlur={onSave}
                                            onInputReady={onInputReady(
                                                contentVarName
                                            )}
                                        />
                                    ),
                                };
                            }
                        );
                        break;
                    case RICH_TEXT_INPUT:
                        inputs = inputsMeta[inputType].map(
                            ({ title, slug }, idx) => {
                                const contentVarName = `${inputType}_${slug}`;
                                const mediaTarget = pageBlock.mediaTargets.find(
                                    (item) => item.type == contentVarName
                                );
                                return {
                                    title,
                                    inputElement: (
                                        <RichTextInput
                                            initialValue={
                                                (inputsContent[
                                                    contentVarName
                                                ] &&
                                                    inputsContent[
                                                        contentVarName
                                                    ]) ||
                                                ''
                                            }
                                            onInput={(data) =>
                                                onInput({
                                                    alias: contentVarName,
                                                    data,
                                                })
                                            }
                                            onBlur={onSave}
                                            onInputReady={onInputReady(
                                                contentVarName
                                            )}
                                            ajax={ajax}
                                            mediaTargetId={
                                                mediaTarget && mediaTarget.id
                                            }
                                        />
                                    ),
                                };
                            }
                        );
                        break;
                    case IMAGE_INPUT:
                        inputs = inputsMeta[inputType].map(
                            ({ title, slug }, idx) => {
                                const contentVarName = `${inputType}_${slug}`;
                                const mediaTarget = pageBlock.mediaTargets.find(
                                    (item) => item.type == contentVarName
                                );
                                return {
                                    title,
                                    inputElement: (
                                        <FileInput
                                            mediaTargetId={
                                                mediaTarget && mediaTarget.id
                                            }
                                            onInputReady={onInputReady(
                                                contentVarName
                                            )}
                                        />
                                    ),
                                };
                            }
                        );
                        break;
                    default:
                        break;
                }
                return [...acc, ...inputs];
            },
            []
        );

        const onChangeMode = (newMode) => {
            if (newMode == mode) {
                onExpandedStateChange({
                    body: !pageBlock.expanded.body,
                });
            } else {
                setMode(newMode);
                if (!pageBlock.expanded.body) {
                    onExpandedStateChange({
                        body: true,
                    });
                }
            }
        };

        return (
            <li className='editor__block' key={pageBlock.id}>
                <div
                    className={`box box-primary ${
                        pageBlock.expanded.body ? '' : 'collapsed-box'
                    }`}
                >
                    <div
                        className='box-header with-border'
                        style={{ display: 'flex' }}
                    >
                        <div
                            className='box-title'
                            onClick={() => {
                                onExpandedStateChange({
                                    body: !pageBlock.expanded.body,
                                });
                            }}
                        >
                            <h5>{pageBlock.blogBlock.name}</h5>
                        </div>
                        <div
                            className='btn-group'
                            style={{ marginLeft: 'auto', marginRight: 30 }}
                        >
                            <button
                                type='button'
                                onClick={() => {
                                    onChangeMode('editor');
                                }}
                                className={`btn btn-default ${
                                    mode == 'editor' &&
                                    pageBlock.expanded.body &&
                                    'bg-gray'
                                }`}
                            >
                                <i className={`fa fa-paragraph`}></i>
                            </button>
                            <button
                                type='button'
                                className={`btn btn-default ${
                                    mode == 'settings' &&
                                    pageBlock.expanded.body &&
                                    'bg-gray'
                                }`}
                                onClick={() => {
                                    onChangeMode('settings');
                                }}
                            >
                                <i className='fa fa-gear'></i>
                            </button>
                        </div>
                        <div className='btn-group'>
                            <button
                                type='button'
                                onClick={onSave}
                                disabled={!isDirty}
                                className={`btn btn-default ${
                                    isDirty && 'bg-olive'
                                }`}
                            >
                                <i className={`fa fa-save`}></i>
                            </button>
                            <button type='button' className='btn btn-default'>
                                <i className='fa fa-refresh'></i>
                            </button>
                            <button
                                type='button'
                                className={`btn btn-default drag-btn`}
                            >
                                <i className={`fa fa-arrows drag-btn`}></i>
                            </button>
                            <button
                                type='button'
                                className='btn btn-default'
                                onClick={onDeleteBlock}
                            >
                                <i className='fa fa-close'></i>
                            </button>
                        </div>
                    </div>
                    <div
                        className={`box-body ${
                            mode == 'editor' ? '' : 'hidden'
                        }`}
                    >
                        {inputComponents.map(({ title, inputElement }, idx) => (
                            <div className='form-group' key={idx}>
                                <label>{title}</label>
                                {inputElement}
                            </div>
                        ))}
                    </div>
                    <div
                        className={`box-body ${
                            mode == 'settings' ? '' : 'hidden'
                        }`}
                    >
                        <div className='box-body'>
                            <div className='form-group_row'>
                                <div className=''>Ширина блока</div>
                                <Select
                                    defaultValue={SETTINGS_SIZES.find(
                                        ({ value }) =>
                                            value == inputsContent.setting_size
                                    )}
                                    options={SETTINGS_SIZES}
                                    onChange={({ value }) =>
                                        onSettingChange({
                                            settingVarName: 'setting_size',
                                            value,
                                        })
                                    }
                                    isSearchable={false}
                                />
                            </div>
                            <div className='form-group_row'>
                                <div className=''>Отступы</div>
                                <Select
                                    defaultValue={SETTINGS_MARGIN.find(
                                        ({ value }) =>
                                            value ==
                                            inputsContent.setting_margin
                                    )}
                                    options={SETTINGS_MARGIN}
                                    onChange={({ value }) =>
                                        onSettingChange({
                                            settingVarName: 'setting_margin',
                                            value,
                                        })
                                    }
                                    isSearchable={false}
                                />
                            </div>
                            <div className='form-group_row'>
                                <div className=''>Цвет</div>
                                <Select
                                    defaultValue={SETTINGS_COLORS.find(
                                        ({ value }) =>
                                            value == inputsContent.setting_color
                                    )}
                                    options={SETTINGS_COLORS}
                                    onChange={({ value }) =>
                                        onSettingChange({
                                            settingVarName: 'setting_color',
                                            value,
                                        })
                                    }
                                    isSearchable={false}
                                />
                            </div>
                            {customSettings.map(
                                ({
                                    title,
                                    type,
                                    variants,
                                    defaultValue,
                                    settingVarName,
                                }) =>
                                    type == 'select' && (
                                        <div
                                            className='form-group_row'
                                            key={settingVarName}
                                        >
                                            <div className=''>{title}</div>
                                            <Select
                                                defaultValue={variants.find(
                                                    ({ value }) =>
                                                        value ==
                                                        inputsContent[
                                                            settingVarName
                                                        ]
                                                )}
                                                options={variants}
                                                onChange={({ value }) =>
                                                    onSettingChange({
                                                        settingVarName,
                                                        value,
                                                    })
                                                }
                                                isSearchable={false}
                                            />
                                        </div>
                                    )
                            )}
                            <div className='form-group_row form-group_row__checkbox'>
                                <label>
                                    <span>Выводить в оглавление</span>
                                    <Checkbox
                                        onChange={onParagraphCheckbox}
                                        defaultChecked={
                                            inputsContent.paragraph.isset
                                        }
                                    />
                                </label>
                                <label
                                    className={
                                        inputsContent.paragraph.isset &&
                                        headingVar != ''
                                            ? ''
                                            : 'hidden_soft'
                                    }
                                >
                                    <span>Пункт оглавления = заголовку</span>
                                    <Checkbox
                                        onChange={onParagraphAsHeadingCheckbox}
                                        defaultChecked={
                                            inputsContent.paragraph.asHeading
                                        }
                                    />
                                </label>
                            </div>
                            {inputsContent.paragraph.isset &&
                                !inputsContent.paragraph.asHeading && (
                                    <div className='form-group'>
                                        <label htmlFor=''>
                                            Введите название пункта в содержании
                                        </label>
                                        <input
                                            type='text'
                                            className='form-control'
                                            placeholder='Название пункта в содержании'
                                            onChange={onParagraphHeadingChange}
                                            defaultValue={
                                                inputsContent.paragraph.heading
                                            }
                                        />
                                    </div>
                                )}
                        </div>
                    </div>
                </div>
            </li>
        );
    }
);

const SortableList = SortableContainer(({ children }) => {
    return <ul>{children}</ul>;
});

const Editor = ({
    ajax,
    pageId,
    newBlock,
    setNewBlock,
    shouldAllBlocksExpand,
    setShouldAllBlocksExpand,
}) => {
    const [pageBlocks, setPageBlocks] = useState([]);

    const setNewPageBlocks = (newPageBlocks) => {
        const withExpandedState = newPageBlocks.map((pageBlock, idx, arr) => ({
            ...pageBlock,
            expanded: { body: idx === arr.length - 1 },
        }));
        // const sortedBlocks = _.orderBy(withExpandedState, 'sort', 'asc');
        setPageBlocks(withExpandedState);
    };

    const addBlock = (item) => {
        setNewPageBlocks([...pageBlocks, item]);
        refreshSortings();
    };
    const removeBlock = (id) => {
        ajax(
            'delete',
            PUT_PAGE_BLOCKS + '/' + id + '/',
            (data, textStatus, xhr) => {
                if (xhr.status == 204) {
                    setNewPageBlocks(
                        pageBlocks.filter((item) => item.id !== id)
                    );
                }
            }
        );
    };
    const onDeleteBlock = (id) => () => {
        const result = confirm('Вы уверены что хотите удалить блок?');
        if (result) {
            removeBlock(id);
        }
    };
    const refreshSortings = () => {
        const updateData = pageBlocks.reduce((acc, pageBlock, idx) => {
            const sortIndex = idx + 1;
            if (pageBlock.sort != sortIndex) {
                return { ...acc, [pageBlock.id]: sortIndex };
            }
            return acc;
        }, {});
        if (!_.isEmpty(updateData)) {
            ajax(
                'post',
                POST_BLOCKS_SORTINGS,
                (rowsUpdated) => {
                    if (_.size(updateData) == rowsUpdated) {
                        setNewPageBlocks(
                            pageBlocks.map((item, idx) => {
                                item.sort = idx + 1;
                                return item;
                            })
                        );
                    }
                },
                { items: JSON.stringify(updateData) },
                (res) => console.log(res)
            );
        }
    };
    const createNewPageBlock = () => {
        if (newBlock !== null) {
            ajax(
                'post',
                PAGE_BLOCKS,
                (res) => {
                    addBlock(res);
                },
                {
                    [PAGE_ID_REL]: pageId,
                    [BLOCK_ID_REL]: newBlock.id,
                },
                (res) => {},
                setNewBlock(null)
            );
        }
    };

    const expandAllBlocks = (state) => {
        const newPageBlocks = pageBlocks.map((pageBlock, idx, arr) => ({
            ...pageBlock,
            expanded: { body: state },
        }));
        setPageBlocks(newPageBlocks);
    };

    useEffect(() => {
        createNewPageBlock();
    }, [newBlock]);

    useEffect(() => {
        if (shouldAllBlocksExpand !== null) {
            expandAllBlocks(shouldAllBlocksExpand);
            setShouldAllBlocksExpand(null);
        }
    }, [shouldAllBlocksExpand]);

    useEffect(
        () =>
            ajax(
                'get',
                PAGE_BLOCKS + '&' + 'filter[blog_post_id]=' + pageId,
                (res) => setNewPageBlocks(res.items || [])
            ),
        []
    );

    useEffect(() => {
        refreshSortings();
    }, [pageBlocks]);

    const onSortEnd = ({ oldIndex, newIndex, collection, isKeySorting }) => {
        const sortedBlocks = arrayMove(pageBlocks, oldIndex - 1, newIndex - 1);
        setNewPageBlocks(sortedBlocks);
    };

    const shouldCancelStart = (e) => {
        if (!e.target.classList.contains('drag-btn')) {
            return true;
        }
    };

    const onExpandedStateChange = (id) => ({ body }) => {
        const newPageBlocks = pageBlocks.map((pageBlock) => {
            if (id == pageBlock.id) {
                return { ...pageBlock, expanded: { body } };
            }
            return pageBlock;
        });
        setPageBlocks(newPageBlocks);
    };

    const onBlockLoaded = (id) => (state) => {
        const newPageBlocks = pageBlocks.map((pageBlock) => {
            if (id == pageBlock.id) {
                return { ...pageBlock, loaded: state };
            }
            return pageBlock;
        });
        setPageBlocks(newPageBlocks);
    };

    const percentOfBlocksLoaded =
        pageBlocks.length > 0
            ? (100 / pageBlocks.length) *
              pageBlocks.filter((block) => block.loaded).length
            : 0;
    const isFullyLoaded = Math.round(percentOfBlocksLoaded) === 100;

    return (
        <div className='constructor__editor editor'>
            <div
                className={`progress-bar-container ${
                    isFullyLoaded ? 'hidden' : ''
                }`}
            >
                {percentOfBlocksLoaded > 0 ? (
                    <Line
                        percent={percentOfBlocksLoaded}
                        strokeWidth='5'
                        strokeColor='#2D6BC0'
                        style={{ width: '50%' }}
                    />
                ) : (
                    ''
                )}
            </div>
            <div className={!isFullyLoaded ? 'hidden' : ''}>
                <SortableList
                    onSortEnd={onSortEnd}
                    shouldCancelStart={shouldCancelStart}
                >
                    {pageBlocks.map((pageBlock, idx) => (
                        <SortableItem
                            key={`item_${pageBlock.id}`}
                            index={pageBlock.sort}
                            item={pageBlock}
                            ajax={ajax}
                            onDeleteBlock={onDeleteBlock(pageBlock.id)}
                            onExpandedStateChange={onExpandedStateChange(
                                pageBlock.id
                            )}
                            onBlockLoaded={onBlockLoaded(pageBlock.id)}
                        />
                    ))}
                </SortableList>
            </div>
        </div>
    );
};

const ToolsPanel = ({
    ajax,
    onBlockBtnClick,
    setShouldAllBlocksExpand,
    frontendDomen,
    pageId,
}) => {
    const [availableBlocks, setAvailableBlocks] = useState([]);
    useEffect(() => {
        ajax('get', AVAILABLE_BLOCKS, (res) => {
            setAvailableBlocks(res.items || []);
        });
    }, []);
    const groups = availableBlocks.reduce((acc, block) => {
        return {
            ...acc,
            [block.type]: {
                label: block.blockTypeLabel,
                items: [
                    ...((acc[block.type] && acc[block.type].items) || []),
                    block,
                ],
            },
        };
    }, {});
    return (
        <div className='constructor__tools-panel'>
            <Sticky enabled={true} top={50} innerClass='tools-panel'>
                {_.keys(groups).map((key, idx) => {
                    const { label, items } = groups[key];
                    return (
                        <div className='form_group' key={idx}>
                            <label>{label}</label>
                            <ul>
                                {items.map((block, idx) => (
                                    <li key={idx}>
                                        <button
                                            className='btn mr-5 mb-5 btn-default'
                                            onClick={(e) =>
                                                onBlockBtnClick(block)
                                            }
                                        >
                                            {block.name}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    );
                })}
                <div className='form_group'>
                    <label>Настройки</label>
                    <ul>
                        <li>
                            <button
                                className='btn btn-sm mr-5 mb-5 btn-default'
                                onClick={(e) => {
                                    setShouldAllBlocksExpand(false);
                                }}
                            >
                                Свернуть все
                            </button>
                            <button
                                className='btn btn-sm mr-5 mb-5 btn-default'
                                onClick={(e) => {
                                    setShouldAllBlocksExpand(true);
                                }}
                            >
                                Развернуть все
                            </button>
                            <a
                                target='_blank'
                                href={`${frontendDomen}/blog/preview-post/${pageId}/`}
                            >
                                <button
                                    className='btn btn-sm mr-5 mb-5 btn-default'
                                    onClick={(e) => {
                                        setShouldAllBlocksExpand(true);
                                    }}
                                >
                                    Предпросмотр&nbsp;
                                    <i className='fa fa-external-link'></i>
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </Sticky>
        </div>
    );
};

const App = ({ pageId, frontendDomen }) => {
    const [isFetching, setIsFetching] = useState(false);
    const [newBlock, setNewBlock] = useState(null);
    const [shouldAllBlocksExpand, setShouldAllBlocksExpand] = useState(null);

    const ajax = (
        method,
        url,
        success = () => {},
        data = {},
        error = (err) => {
            console.log(err);
        },
        callback = () => {}
    ) => {
        if (!isFetching) {
            setIsFetching(true);
            console.log('sending ' + method, data);
            $.ajax({
                url: `/${url}`,
                type: method,
                method,
                cache: false,
                data,
                dataType: 'json',
                success: (res, textStatus, xhr) => {
                    success(res, textStatus, xhr);
                    setIsFetching(false);
                },
                error: (res) => {
                    error(res);
                    setIsFetching(false);
                },
            });
        }
        callback();
    };
    const onBlockBtnClick = (block) => {
        setNewBlock(block);
    };

    const props = {
        onBlockBtnClick,
        ajax,
        newBlock,
        pageId,
        setNewBlock,
        shouldAllBlocksExpand,
        setShouldAllBlocksExpand,
        frontendDomen,
    };
    return (
        <div className='constructor'>
            <ToolsPanel {...props} />
            <Editor {...props} />
        </div>
    );
};

$('document').ready(() => {
    const domContainer = document.getElementById('react-constructor');
    const pageId = $('[data-page-id]') && $('[data-page-id]').data('page-id');
    const frontendDomen =
        $('[data-frontend]') && $('[data-frontend]').data('frontend');
    if (pageId) {
        ReactDOM.render(
            <App pageId={pageId} frontendDomen={frontendDomen} />,
            domContainer
        );
    }
});
