# Transfer codebase to GitHub

- <https://repo.code.ubc.ca/smarsh05/ipeer-lti13/-/tree/lti-1.3-manual-roster-update>
- <https://github.com/ubc/iPeer>

## Add SSH key

<https://github.com/settings/ssh/new>

```bash
pbcopy < ~/.ssh/id_rsa.pub
```

Paste it in "Key" testarea on "SSH keys / Add new" page.

## Clone

```bash
cd ~/Code/ctlt
git clone --branch lti-1.3-roster-update https://repo.code.ubc.ca/smarsh05/ipeer-lti13
cd ~/Code/ctlt/ipeer-lti13
git tag -a 3.4.5 -m "LTI 1.3 plus roster update"
git remote set-url origin git@github.com:ubc/iPeer.git
git remote -v
git push --set-upstream origin lti-1.3-roster-update
git push origin --tags
```

## Delete local GitLab dirtree

```bash
cd ~/Code/ctlt
rm -rf ~/Code/ctlt/iPeer
mv ipeer-lti13 iPeer
cd ~/Code/ctlt/iPeer
```

## Create a Pull Request

<https://github.com/ubc/iPeer/pull/new/lti-1.3-roster-update>
