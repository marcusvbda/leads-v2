module.exports = {
    root: true,
    env: {
        node: true,
    },
    extends: ["eslint:recommended", "plugin:vue/essential"],
    parserOptions: {
        parser: "babel-eslint",
    },
    rules: {
        "no-console": "off",
        "no-debugger": "off",
        "no-undef": "off",
        "vue/no-mutating-props": "off",
        "vue/valid-template-root": "off",
        "linebreak-style": 0,
        indent: ["error", 4, { MemberExpression: 1 }],
        "vue/html-closing-bracket-newline": [
            "error",
            {
                singleline: "never",
                multiline: "always",
            },
        ],
        "max-len": ["error", { code: 130 }],
    },
};
