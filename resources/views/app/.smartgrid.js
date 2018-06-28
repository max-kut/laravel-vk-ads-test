let smartgrid = require('smart-grid');

smartgrid('./src', {
    outputStyle: 'scss', /* less || scss || sass || styl */
    columns: 24, /* number of grid columns */
    offset: '20px', /* gutter width px || % */
    mobileFirst: true, /* mobileFirst ? 'min-width' : 'max-width' */
    container: {
        maxWidth: '1200px', /* max-width оn very large screen */
        // offset: '20px',
        fields: '10px' /* side fields */
    },
    breakPoints: {
        xs: {
            width: '560px',
        },
        sm: {
            width: '780px',
            // offset: '15px',
            // fields: '15px'
        },
        md: {
            width: '960px',
            // offset: '20px',
            // fields: '20px'
        },
        lg: {
            width: '1100px', /* -> @media (max-width: 1100px) */
            // offset: '20px',
            // fields: '20px'
        },
        /*
        We can create any quantity of break points.

        some_name: {
            width: 'Npx',
            fields: 'N(px|%|rem)',
            offset: 'N(px|%|rem)'
        }
        */
    }
});
