var mySettings = {
    nameSpace: 'xbbcode',
    onShiftEnter: {
        keepDefault: false,
        replaceWith: '[br /]\n'
    },
    onCtrlEnter: {
        keepDefault: false,
        openWith: '\n[p]',
        closeWith: '[/p]\n'
    },
    onTab: {
        keepDefault: false,
        openWith: '    '},
    markupSet: [
        { name: 'Paragraph', openWith: '[p(!( class="[![Class]!]")!)]', closeWith: '[/p]' },
        { name: 'Bold', key: 'B', openWith:'(!([strong]|!|[b])!)', closeWith: '(!([/strong]|!|[/b])!)' },
        { name: 'Italic', key: 'I', openWith: '(!([em]|!|[i])!)', closeWith: '(!([/em]|!|[/i])!)' },
        { name: 'Link', key: 'L', openWith: '[a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)]', closeWith: '[/a]', placeHolder: 'Your text to link...' },
        { name: 'Big', openWith: '[big]', closeWith: '[/big]' },
        { name: 'Small', openWith: '[small]', closeWith: '[/small]' }
    ]
};
