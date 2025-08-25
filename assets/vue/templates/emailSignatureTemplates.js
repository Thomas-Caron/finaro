export const emailSignatureTemplates = {
    'template-1': {
        blocks: [
            { id: 'container', type: 'container' },
            { id: 'col1', type: 'column', position: 'left' },
            { id: 'col2', type: 'column', position: 'right' },
            { id: 'b_img', type: 'image', value: 'https://webgroup.img.email/placeholders/profile-photo-1.png', parent: 'col1' },
            { id: 'b1', type: 'text', value: 'Sheri Watkins', parent: 'col2' },
            { id: 'b2', type: 'text', value: 'Department Chair | Columbus University', parent: 'col2' },
            { id: 'b3', type: 'text', value: '333 S 3rd St. Somewhereville, VA', parent: 'col2' },
            { id: 'b4', type: 'url', value: 'https://columbusuniversity.org', label: 'columbusuniversity.org', parent: 'col2' },
        ],
        styles: {
            container: {
                backgroundColor: '#ffffff',
                width: '450px',
                fontFamily: 'Helvetica, Arial, sans-serif'
            },
            col1: {
                width: '150px',
                paddingRight: '15px',
            },
            col2: {
                width: '300px',
                paddingLeft: '15px',
            },
            b_img: {
                width: '150px',
                borderRadius: '100%',
            },
            b1: {
                color: '#0085d1',
                fontWeight: 'bold',
                fontSize: '16px',
            },
            b2: {
                fontWeight: 'bold',
                fontSize: '13px',
                paddingBottom: '7px',
                borderBottom: '2px',
                borderBottomStyle: 'solid',
                borderBottomColor: '#0085d1',
            },
            b3: {
                fontSize: '13px',
                paddingTop: '7px'
            },
            b4: {
                fontSize: '13px',
                color: '#00a8e2',
                textDecoration: 'none'
            }
        }
    },

    'template-2': {
        blocks: [
            { id: 'container', type: 'container' },
            { id: 'col1', type: 'column', position: 'left' },
            { id: 'col2', type: 'column', position: 'right' },
            { id: 'b_img', type: 'image', value: 'https://webgroup.img.email/placeholders/profile-photo-1.png', parent: 'col1' },
            { id: 'b1', type: 'text', value: 'Sheri Watkins', parent: 'col2' },
            { id: 'b2', type: 'text', value: 'Department Chair | Columbus University', parent: 'col2' },
            { id: 'b3', type: 'text', value: '999-999-9999', parent: 'col2' },
            { id: 'b4', type: 'text', value: '333 S 3rd St. Somewhereville, VA 33333', parent: 'col2' },
            { id: 'b5', type: 'url', value: 'https://columbusuniversity.org', label: 'columbusuniversity.org', parent: 'col2' },
        ],
        styles: {
            container: {
                backgroundColor: '#ffffff',
                width: '420px',
                fontFamily: 'Helvetica, Arial, sans-serif'
            },
            col1: {
                width: '120px',
                paddingRight: '15px',
                borderRight: '3px',
                borderRightStyle: 'solid',
                borderRightColor: '#eeeeee',
            },
            col2: {
                width: '300px',
                paddingLeft: '15px',
            },
            b_img: {
                width: '120px',
                borderRadius: '100%',
            },
            b1: {
                color: '#0085d1',
                fontWeight: 'bold',
                fontSize: '16px',
            },
            b2: {
                fontWeight: 'bold',
                fontSize: '13px',
                paddingBottom: '5px'
            },
            b3: {
                fontSize: '13px',
                color: '#444444'
            },
            b4: {
                fontSize: '13px',
                color: '#444444'
            },
            b5: {
                fontSize: '13px',
                color: '#00a8e2',
            }
        }
    },
    'template-3': {
        blocks: [
            { id: 'container', type: 'container' },
            { id: 'col1', type: 'column', position: 'right' },
            { id: 'col2', type: 'column', position: 'left' },
            { id: 'b_image', type: 'image', value: 'https://webgroup.img.email/placeholders/columbus-mark.png', parent: 'col1' },
            { id: 'b1', type: 'text', value: 'DR. JOSEPH DAVIS', parent: 'col2' },
            { id: 'b2', type: 'text', value: 'ADJUNCT PROFESSOR', parent: 'col2' },
            { id: 'b3', type: 'text', value: '333 S 3rd St. Somewhereville, VA 33333', parent: 'col2' },
            { id: 'b4', type: 'url', value: 'https://columbusuniversity.org', label: 'columbusuniversity.org', parent: 'col2' }
        ],
        styles: {
            container: {
                backgroundColor: '#ffffff',
                width: '351px',
                fontFamily: 'Helvetica, Arial, sans-serif'
            },
            col1: {
                width: '100px',
                paddingLeft: '15px',
                borderLeft: '3px',
                borderLeftStyle: 'solid',
                borderLeftColor: '#eeeeee',
            },
            col2: {
                width: '251px',
                paddingRight: '15px',
            },
            b_image: {
                width: '100px',
            },
            b1: {
                color: '#0085d1',
                fontWeight: 'bold',
                fontSize: '16px',
                textTransform: 'uppercase',
            },
            b2: {
                color: '#A2A2A2',
                fontSize: '11px',
                textTransform: 'uppercase',
                letterSpacing: '2px',
            },
            b3: {
                fontSize: '13px',
                paddingTop: '6px'
            },
            b4: {
                fontSize: '13px',
                fontWeight: 'bold',
                color: '#00a8e2',
            }
        }
    }
};